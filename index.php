<?php

require_once 'vendor/autoload.php';

use EcomerceBy1ya\Product;
use EcomerceBy1ya\ProductForm;

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

//$product = new Product(['naziv' => 'Galaxy S5', 'cena' => 350, 'id_kat' => 1, 'id_spec' => 1, 'id_marka' => 1,'availibleQuantity' => 10]);

//$product->createData();
try {
    $product = Product::getByIdFromDb(17);
} catch (Exception $e){
    echo $e->getMessage();
    exit;
}


$productForm = ProductForm::loadProduct($product);
var_dump($productForm);

//var_dump($productForm);

//$product->deleteData();

//Product::fetchData();
