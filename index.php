<?php

require_once 'vendor/autoload.php';

// require_once "Product.php";
// require_once "Cart.php";
// require_once "CartItem.php";

/* $product1 = new EcomerceBy1ya\Product(1, "iPhone 13", 2500, 10);
$product2 = new EcomerceBy1ya\Product(2, "NvMe SSD", 400, 10);
$product3 = new EcomerceBy1ya\Product(3, "Samsung Galaxy S20", 2500, 10); */

$cart = new EcomerceBy1ya\Cart();

/*$cartItem1 = $cart->addProduct($product1, 2);
$cartItem2 = $cart->addProduct($product2, 5);
$cartItem3 = $cart->addProduct($product3, 3); */



// echo $cart->getTotalQuantity(). " total quantity" . PHP_EOL;
// echo $cart->getTotalSum(). " total sum" . PHP_EOL;


// $cartItem2->decreaseQuantity(2);



// echo $cart->getTotalQuantity(). " total quantity" . PHP_EOL;
// echo $cart->getTotalSum(). " total sum" . PHP_EOL;

$db = new PDO('mysql:host=localhost;dbname=Ecommerce1ya', 'root', 'new-password', []);


function fetchData($db)
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

        $stmt = $db->prepare($query);

        $marka = 'Samsung';
        $stmt->bindValue('marka', $marka);

        $stmt->execute();

        foreach($stmt as $product){
            echo $product['naziv_marke'] . ' ' . $product['naziv_proizvod']. PHP_EOL;
        }

    } catch (PDOException $e) {

        throw new PDOException($e->getMessage(), $e->getCode());
    }
};



function insertData($db)
{
    try {
        $query = 'INSERT INTO Proizvod (naziv_proizvod, cena_proizvod, id_kategorija, id_specifikacija, id_marka)
                  VALUES (:naziv, :cena, :kat, :spec, :marka)'; 
                  
        $stmt = $db->prepare($query);

        $naziv = 'Galaxy S5';
        $cena = 300;
        $kat= 1;
        $spec = 1;
        $marka = 1;

        $stmt->bindValue(':naziv', $naziv);
        $stmt->bindValue(':cena', $cena);
        $stmt->bindValue(':kat', $kat);
        $stmt->bindValue(':spec', $spec);
        $stmt->bindValue(':marka', $marka);

        $stmt->execute();

        foreach($stmt as $product){
            echo $product['naziv_marke'] . ' ' . $product['naziv_proizvod']. PHP_EOL;
        }

        fetchData($db);

    } catch (PDOException $e) {

        throw new PDOException($e->getMessage(), $e->getCode());
    }
};

insertData($db);
