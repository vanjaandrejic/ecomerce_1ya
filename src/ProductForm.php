<?php

namespace EcomerceBy1ya;

class ProductForm extends Product
{
    protected $productFormArr = ['id', 'naziv', 'cena', 'id_kat', 'id_spec', 'id_marka', 'availibleQuantity'];

    public function __construct()
    {

    }

    public static function loadProduct($product)
    {
        $form = new self();

        foreach($form->productFormArr as $key){
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

    public function updateForm($niz)
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
}