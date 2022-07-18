<?php

namespace EcomerceBy1ya;

class ProductForm extends Model
{
    public int $id;
    public string $naziv;
    public float $cena;
    public int $id_kat;
    public int $id_spec;
    public int $id_marka;
    public int $availibleQuantity;

    protected $productFormArr = ['naziv', 'cena', 'id_kat', 'id_spec', 'id_marka', 'availibleQuantity'];

    public function __construct()
    {

    }

    public static function loadProduct($product)
    {
        $form = new self();

        foreach(get_object_vars($product) as $key=>$value){
            $form->$key = $product->$key;
        }
        
        // $form->id = $product->id;
        // $form->naziv = $product->naziv;
        // $form->cena = $product->cena;
        // $form->id_kat = $product->id_kat;
        // $form->id_spec = $product->id_spec;
        // $form->id_marka = $product->id_marka;
        // $form->availibleQuantity = $product->availibleQuantity;

        return $form;
    }

    public function updateForm($niz) //dobija se updated objekat forme
    {
        
        foreach ($niz as $key=>$value) {
                
            // proverava da li property postoji u nizu productFormArr
             if(in_array($key, $this->productFormArr)) {

            // ako postoji property u objektu dodeljuje mu vrednost
                if (property_exists($this, $key)){
                    $this->$key = $value;
                }
            }
        }
        return $this;
    }
}