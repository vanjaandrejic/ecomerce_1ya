<?php

namespace EcomerceBy1ya;

use Exception;
use PDO;

abstract class Model
{

    protected static abstract function tableName();
    private static $db = null;
    private static $query = '';
    private static $bindParams = [];
    private static $preparedQuery = [];
    private static $results = [];


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

        $stmt->bindValue(':id', (int) $id);

        $stmt->execute();
        $niz = $stmt->fetch();

        if (empty($niz)) {
            throw new Exception("Proizvod pod id $id ne postoji");
        }
        return new Product($niz);
    }



    public function load($obj)
    {
        foreach (get_object_vars($obj) as $key => $value) {
            //var_dump($key);
            if (!property_exists($this, 'id') || !property_exists($obj, 'id') || $this->id != $obj->id) {
                throw new Exception("ID se ne podudaraju!");
            }
            if (property_exists($this, $key)) {
                $this->$key = $obj->$key;
            }
        }
        return $this;
    }



    public static function find($select = [])   // vraca SELECT * FROM
    {
        $tableName = static::tableName();

        self::$query .= 'SELECT ';
        if (!empty($select)) {
            foreach ($select as $param) {
                self::$query .= $param;
            }
        } else {
            self::$query .= '*';
        }

        self::$query .= ' FROM ' . $tableName;

        //var_dump(self::$query);

        return self::$query;
    }



    public static function join($table, $col1, $col2, $type = ' INNER')
    {
        $tableName = static::tableName();
        self::$query .= $type . ' JOIN ' . $table . ' ON ' . $tableName . '.' . $col1 . '=' . $table . '.' . $col2;  // ???
    }



    public static function where($table, $col, $value)
    {
        self::queryValidate();

        $strWhere = ' WHERE ';
        $bindStr = ':' . $col;
        $pos = strpos(self::$query, $strWhere);

        if ($pos) {
            $strWhere = ' AND ';
        }
        self::$query .= $strWhere . $table . '.' . $col . '=' . $bindStr;
        self::$bindParams[$bindStr] = $value;
    }



    public static function orderBy($table, $col)
    {
        $strOrder = ' ORDER BY ';

        $pos = strpos(self::$query, $strOrder);

        if (!$pos) {

            self::$query .= $strOrder . $table . '.' . $col;
        }
    }



    public static function prepareQuery()
    {
        try {

            $stmt = self::getDb()->prepare(self::$query);

            if (!empty(self::$bindParams)) {
                foreach (self::$bindParams as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
            }

            $stmt->execute();

            self::$preparedQuery = $stmt->fetchAll();
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }



    private static function queryValidate()
    {
        if (!strpos(self::$query, 'FROM') && !strpos(self::$query, 'SELECT')) {
            throw new Exception("WHERE mora biti zakacen nakon SELECT i FROM");
        }
    }



    static function all($niz)
    {
        if (!empty(self::$preparedQuery)) {
            foreach (self::$preparedQuery as $key => $value) {
                foreach ($niz as $col) {
                    if ($col == $key) {
                        self::$results[$col] = $value;

                        var_dump($value);
                    }
                }
            }
        }
    }

    
}
