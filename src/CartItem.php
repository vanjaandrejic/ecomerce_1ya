<?php

namespace EcomerceBy1ya;

class CartItem
{

    private Product $product;
    private int $quantity;

    public function __construct($product, $quantity)
    {

        $this->product = $product;
        $this->quantity = $quantity;
    }


    public function __get($property)
    {
        return $this->$property;
    }


    public function __set($property, $value)
    {
        $this->$property = $value;
    }


    public function increaseQuantity($amount = 1)
    {

        if ($this->quantity + $amount > $this->product->availibleQuantity) {
            throw new \Exception("Product quantity can not be more than " . $this->product->availibleQuantity);
        }

        $this->quantity += $amount;
    }



    public function decreaseQuantity($amount = 1)
    {

        if ($this->quantity - $amount < 1) {
            throw new \Exception("Product quantity can not be less than 1");
        }

        $this->quantity -= $amount;
    } 
};
