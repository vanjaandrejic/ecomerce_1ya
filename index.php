<?php

require_once 'vendor/autoload.php';

use EcomerceBy1ya\Product;
use EcomerceBy1ya\ProductForm;
use EcomerceBy1ya\Specification;
use EcomerceBy1ya\SpecificationForm;
use EcomerceBy1ya\Category;
use EcomerceBy1ya\CategoryForm;
use EcomerceBy1ya\Brand;
use EcomerceBy1ya\BrandForm;


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
     
     var_dump($product);
    // $productForm = ProductForm::loadProduct($product);
    // $productForm->updateForm(['naziv'=>'106 pluss', 'cena'=>28]);


    // $product->load($productForm);
    // $product->updateData();


    //$spec = new Specification(['processor' => 'procesorTEST', 'ram' => 82, 'rom' => 234 ]);
    //$spec->createData();

    //Specification::fetchData();

     $spec = Specification::getByIdFromDb(10);
     var_dump($spec);


    // $specificationForm = SpecificationForm::loadSpec($spec);

    // var_dump($specificationForm);

    // $specificationForm->updateForm(['processor' => 'TESTProc1', 'rom' => 333]); /////////////////

    // var_dump($specificationForm);
    // $spec->load($specificationForm);

    // var_dump($spec);
    // $spec->updateData();
    // Category::fetchData();

    // $cat = new Category(['naziv_cat' => 'Tablet']);
    // $cat->createData();

    // Category::fetchData();

     $cat = Category::getByIdFromDb(2);
     var_dump($cat);
    // $catForm = CategoryForm::loadCat($cat);
    // $catForm->updateForm(['naziv_cat' => 'Laptop']);
    // $cat->load($catForm);
    // $cat->updateData();

    //Category::fetchData();

    //var_dump($brandForm);

    //var_dump($spec1);
    
    //$spec->deleteData();

    $brand = Brand::getByIdFromDb(1);
    var_dump($brand);
    //$brand->createData();

} catch (Exception $e){
    echo $e->getMessage();
    exit;
}