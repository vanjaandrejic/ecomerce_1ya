<?php

namespace EcomerceBy1ya;

use Exception;

class Category extends Model
{
    public int $id;
    public string $naziv_cat;
    private static $db = null;

    protected static function tableName()
    {
        return 'Kategorija';
    }

    private static $dbMapCat = [
        'naziv_kategorije' => 'naziv_cat'
    ];

    public function __construct($niz)
    {
        foreach ($niz as $key => $value) {
            // u slucaju da se poklapa property sa imenima koja se podudaraju baza = php
            if (property_exists($this, $key)) {
                $this->$key = $value;

                // proverava da li property postoji u nizu dbMapper
            } else if (array_key_exists($key, self::$dbMapCat)) {
                $propertyValue = self::$dbMapCat[$key];

                // ako postoji property u objektu dodeljuje mu vrednost
                if (property_exists($this, $propertyValue)) {
                    $this->$propertyValue = $value;
                }
            }
        }
    }


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
        $query = 'SELECT * FROM Kategorija WHERE id = :id';

        $stmt = self::getDb()->prepare($query);

        $stmt->bindValue(':id',(int) $id);

        $stmt->execute();
        $niz = $stmt->fetch();

        if(empty($niz)){
            throw new Exception("Kategorija pod id $id ne postoji");
        }
        return new Category($niz);
    }

    public function load($obj)
    {

        //var_dump(get_object_vars($obj));
        
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


    static function fetchData()
    {
        try {
            $query = 'SELECT * FROM Kategorija';

            $stmt = self::getDb()->prepare($query);

            $stmt->execute();

            $niz = $stmt->fetchAll();

            foreach ($niz as $spec) {

                //echo $product['naziv_marke'] . ' ' . $product['naziv_proizvod']. PHP_EOL; // odnosi se na niz
                echo $spec->naziv_kategorije . ' ' . PHP_EOL;      // odnosi se na objekat
            }
        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    public function createData()
    {
        try {
            $query = 'INSERT INTO Kategorija (naziv_kategorije)
                        VALUES (:naziv_cat)';

            $stmt = self::getDb()->prepare($query);

            $stmt->bindValue(':naziv_cat', $this->naziv_cat);

            $stmt->execute();

        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    public function deleteData()
    {
        try {
            $query = 'DELETE FROM Kategorija WHERE id = :id';

            $stmt = self::getDb()->prepare($query);

            $stmt->bindValue(':id', $this->id);

            $stmt->execute();

        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    public function updateData()
    {
        try {
            $query =    'UPDATE Kategorija SET
                        naziv_kategorije = :naziv_cat
                        WHERE id = :id';

            $stmt = self::getDb()->prepare($query);

            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':naziv_cat', $this->naziv_cat);

            $stmt->execute();

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
}