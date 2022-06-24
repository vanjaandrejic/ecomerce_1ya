<?php

namespace EcomerceBy1ya;

class Product
{

    private int $id;
    private string $title;
    private float $price;
    private int $availibleQuantity;


    public function __construct($id, $title, $price, $availibleQuantity)
    {

        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->availibleQuantity = $availibleQuantity;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

};
