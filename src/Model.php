<?php

namespace EcomerceBy1ya;

use Exception;

abstract class Model {

    protected static abstract function tableName();
    private static $db = null;
    
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
}