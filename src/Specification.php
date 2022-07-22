<?php

namespace EcomerceBy1ya;

use Exception;

class Specification extends Model
{
    public int $id;
    public string $processor;
    public int $ram;
    public int $rom;

    protected static function tableName()
    {
        return 'Specifikacija';
    }

    private static $db = null;

    private static $dbMapSpec = [
        'procesor' => 'processor',
        'ram_memorija' => 'ram',
        'rom_memorija' => 'rom'
    ];

    public function __construct($niz)
    {
        foreach ($niz as $key => $value) {
            // u slucaju da se poklapa property sa imenima koja se podudaraju baza = php
            if (property_exists($this, $key)) {
                $this->$key = $value;

                // proverava da li property postoji u nizu dbMapper
            } else if (array_key_exists($key, self::$dbMapSpec)) {
                $propertyValue = self::$dbMapSpec[$key];

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
        $query = 'SELECT * FROM Specifikacija WHERE id = :id';

        $stmt = self::getDb()->prepare($query);

        $stmt->bindValue(':id',(int) $id);

        $stmt->execute();
        $niz = $stmt->fetch();

        if(empty($niz)){
            throw new Exception("Specifikacija pod id $id ne postoji");
        }
        return new Specification($niz);
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


    // static function fetchData()
    // {
    //     try {
    //         $query = 'SELECT * FROM Specifikacija';

    //         $stmt = self::getDb()->prepare($query);

    //         $stmt->execute();

    //         $niz = $stmt->fetchAll();

    //         foreach ($niz as $spec) {

    //             //echo $product['naziv_marke'] . ' ' . $product['naziv_proizvod']. PHP_EOL; // odnosi se na niz
    //             echo $spec->procesor . ' ' . $spec->ram_memorija . ' ' .$spec->rom_memorija. PHP_EOL;      // odnosi se na objekat
    //         }
    //     } catch (\PDOException $e) {

    //         throw new \PDOException($e->getMessage(), $e->getCode());
    //     }
    // }

    public function createData()
    {
        try {
            $query = 'INSERT INTO Specifikacija (procesor, ram_memorija, rom_memorija)
                        VALUES (:procesor, :ram, :rom)';

            $stmt = self::getDb()->prepare($query);

            $stmt->bindValue(':procesor', $this->processor);
            $stmt->bindValue(':ram', $this->ram);
            $stmt->bindValue(':rom', $this->rom);

            $stmt->execute();

        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    public function deleteData()
    {
        try {
            $query = 'DELETE FROM Specifikacija WHERE id = :id';

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
            $query =    'UPDATE Specifikacija SET
                        procesor = :processor, ram_memorija = :ram, rom_memorija = :rom
                        WHERE id = :id';

            $stmt = self::getDb()->prepare($query);

            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':processor', $this->processor);
            $stmt->bindValue(':ram', $this->ram);
            $stmt->bindValue(':rom', $this->rom);

            $stmt->execute();

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    } 
}
