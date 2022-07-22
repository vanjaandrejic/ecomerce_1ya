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


$cart = new EcomerceBy1ya\Cart();

try {

    Product::find(['Proizvod.* ,','Marka.naziv_marke, ', 'Specifikacija.procesor, ', 'Specifikacija.ram_memorija, ', 'Specifikacija.rom_memorija ']);
    Product::join('Marka', 'id_marka', 'id');
    Product::join('Specifikacija', 'id_specifikacija', 'id');
    Product::where('Marka', 'naziv_marke', 'Samsung');
    Product::orderBy('Marka', 'naziv_marke');
    Product::prepareQuery();
    Product::all(['naziv_marke', 'naziv_proizvod', 'procesor']);

} catch (Exception $e){
    echo $e->getMessage();
    exit;
}