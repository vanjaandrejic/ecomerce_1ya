<?php

namespace EcomerceBy1ya;

use Exception;

class Product
{
    protected int $id;
    protected string $naziv;
    protected float $cena;
    protected int $id_kat;
    protected int $id_spec;
    protected int $id_marka;
    protected int $availibleQuantity;
    protected static $db = null;

    protected static $dbMapper = ['cena_proizvod' => 'cena',
                                'dostupna_kolicina' => 'availibleQuantity',
                                'id_kategorija' => 'id_kat',
                                'id_specifikacija' => 'id_spec',
                                'naziv_proizvod' => 'naziv'];

    // public function __construct($id = NULL, $naziv, $cena, $id_kat, $id_spec, $id_marka, $availibleQuantity = 1)
    // {
    //     $this->id = $id;
    //     $this->naziv = $naziv;
    //     $this->cena = $cena;
    //     $this->id_kat = $id_kat;
    //     $this->id_spec = $id_spec;
    //     $this->id_marka = $id_marka;
    //     $this->availibleQuantity = $availibleQuantity;
    // }

    public function __construct($niz)
    {
        foreach ($niz as $key=>$value) {
            // u slucaju da se poklapa property sa imenima koja se podudaraju baza = php
            if (property_exists($this, $key)) {
                $this->$key = $value;

            // proverava da li property postoji u nizu dbMapper
            } else if(array_key_exists($key, self::$dbMapper)) {
                $propertyValue = self::$dbMapper[$key];

            // ako postoji property u objektu dodeljuje mu vrednost
                if (property_exists($this, $propertyValue)){
                    $this->$propertyValue = $value;
                }
            }           
        }
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
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
        $query = 'SELECT * FROM Proizvod WHERE id = :id';

        $stmt = self::getDb()->prepare($query);

        $stmt->bindValue(':id',(int) $id);

        $stmt->execute();
        $niz = $stmt->fetch();

        // if(empty($niz)){
        //     throw new Exception("Proizvod pod id $id ne postoji");
        // }
        return new Product($niz);
    }

    static function fetchData()
    {
        try {
            $query = 'SELECT `Proizvod`.*,
                        `Marka` . `naziv_marke`,
                        `Specifikacija` . `procesor`, `Specifikacija` . `ram_memorija`, `Specifikacija` . `rom_memorija`

                        FROM `Proizvod`
                           
                        INNER JOIN `Marka` ON `Proizvod`. `id_marka` = `Marka`. `id` 
                        INNER JOIN `Specifikacija` ON `Proizvod`. `id_specifikacija` = `Specifikacija`. `id`

                        WHERE `Marka` . `naziv_marke` = :marka

                        ORDER BY `Marka`. `naziv_marke`';

            $stmt = self::getDb()->prepare($query);

            $marka = 'Samsung';
            $stmt->bindValue('marka', $marka);

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

    function createData()
    {
        try {
            $query = 'INSERT INTO Proizvod (naziv_proizvod, cena_proizvod, id_kategorija, id_specifikacija, id_marka, dostupna_kolicina)
                  VALUES (:naziv, :cena, :kat, :spec, :marka, :availibleQuantity)';

            $stmt = self::getDb()->prepare($query);

            $stmt->bindValue(':naziv', $this->naziv);
            $stmt->bindValue(':cena', $this->cena);
            $stmt->bindValue(':kat', $this->id_kat);
            $stmt->bindValue(':spec', $this->id_spec);
            $stmt->bindValue(':marka', $this->id_marka);
            $stmt->bindValue(':availibleQuantity', $this->availibleQuantity);

            $stmt->execute();

        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function deleteData()
    {
        try {
            $query = 'DELETE FROM Proizvod WHERE id = :id';

            $stmt = self::getDb()->prepare($query);

            $stmt->bindValue(':id', $this->id);

            $stmt->execute();

        } catch (\PDOException $e) {

            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }
};
