<?php

namespace EcomerceBy1ya;

use Exception;

abstract class Model {

    protected static abstract function tableName();
    private static $db = null;
    private static $query = null;
    private static $bindParams = [];
    
    protected static function getDb(): \PDO
    {
        if (self::$db === null) {
            $config = include __DIR__ . '/config.php';
            $db = $config['db'];
            $dsn = "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'] . ";";

            self::$db = new \PDO($dsn, $db['username'], $db['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
            ]);
        }
        return self::$db;
    }


    public static function getByIdFromDb($id)
    {
        $tableName = static::tableName();

        $query = "SELECT * FROM $tableName WHERE id = :id";

        $stmt = self::getDb()->prepare($query);

        $stmt->bindValue(':id',(int) $id);

        $stmt->execute();
        $niz = $stmt->fetch();

        if(empty($niz)){
            throw new Exception("Proizvod pod id $id ne postoji");
        }
        return new Product($niz);
    }


    public function load($obj)
    {
        foreach(get_object_vars($obj) as $key=>$value){
            //var_dump($key);
            if (!property_exists($this, 'id') || !property_exists($obj, 'id') || $this->id != $obj->id) {
                throw new Exception("ID se ne podudaraju!");
            }
            if (property_exists($this, $key)){
                $this->$key = $obj->$key; 
            }
        }
        return $this;
    }


    public static function find($select = [])   // vraca SELECT * FROM
    {
        $tableName = static::tableName();

        self::$query .= 'SELECT ';
        if(!empty($select)){
            foreach($select as $param) {
                self::$query .= $param;
            }
        } else {
            self::$query .= '*';
        }

        self::$query .= ' FROM '. $tableName;

        return self::$query;
    }

    public static function join($type = 'INNER', $table, $col1, $col2)
    {
        $tableName = static::tableName();
        self::$query .= $type.' JOIN ' . $table . ' ON '. $tableName . $col1 .'='. $table . $col2;  // ???
    }


    public static function where($table, $col, $value)
    {
        $strWhere =' WHERE ';
        $bindStr = ':'.$col;
        $pos = strpos(self::$query, $strWhere);

        if($pos){
            $strWhere = ' AND ';
        }
        self::$query .= $strWhere . $table . '.' . $col . $bindStr;
        self::$bindParams[] = [$bindStr => $value];
    }

    public static function orderBy($table, $col)
    {
        $strOrder = ' ORDER BY ';

        $pos = strpos(self::$query, $strOrder);

        if(!$pos){

            self::$query .= $strOrder . $table . $col;
        }
    }

    public static function prepareQuery()
    {
        try {

            $stmt = self::getDb()->prepare(self::$query);

            $stmt->execute();

            $niz = $stmt->fetchAll();

            //var_dump($niz);

            foreach ($niz as $product) {

                //echo $product['naziv_marke'] . ' ' . $product['naziv_proizvod']. PHP_EOL; // odnosi se na niz
                echo $product->naziv_marke . ' ' . $product->naziv_proizvod . PHP_EOL;      // odnosi se na objekat
            }
        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }
        
    //Product::find('marka', '');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    static function fetchData()
    {
        $tableName = static::tableName();
        try {
            $query = 'SELECT `Proizvod`.*,
                        `Marka` . `naziv_marke`,
                        `Specifikacija` . `procesor`,
                        `Specifikacija` . `ram_memorija`,
                        `Specifikacija` . `rom_memorija`

                        FROM `Proizvod`

                        INNER JOIN `Marka` ON `Proizvod`. `id_marka` = `Marka`. `id` 
                        INNER JOIN `Specifikacija` ON `Proizvod`. `id_specifikacija` = `Specifikacija`. `id`

                        WHERE `Marka` . `naziv_marke` = :marka

                        ORDER BY `Marka`. `naziv_marke`';

            $stmt = self::getDb()->prepare($query);

            $marka = 'Samsung';
            $stmt->bindValue(':marka', $marka);

            $stmt->execute();

            $niz = $stmt->fetchAll();

            //var_dump($niz);

            foreach ($niz as $product) {

                //echo $product['naziv_marke'] . ' ' . $product['naziv_proizvod']. PHP_EOL; // odnosi se na niz
                echo $product->naziv_marke . ' ' . $product->naziv_proizvod . PHP_EOL;      // odnosi se na objekat
            }
        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }
}