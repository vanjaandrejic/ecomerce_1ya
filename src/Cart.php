<?php

namespace EcomerceBy1ya;

class Cart
{

    private array $items = [];

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }



    public function addProduct(Product $product, int $quantity)
    {
        $cartItem = $this->findCartItem($product->id);

        if ($cartItem == null) {

            $cartItem = new \EcomerceBy1ya\CartItem($product, 0);
            $this->items[$product->id] = $cartItem;
        }

        $cartItem->increaseQuantity($quantity);
        return $cartItem;
    }



    public function findCartItem(int $productId)
    {
        return $this->items[$productId] ?? null;
    }


    public function getTotalQuantity()
    {
        $sum = 0;
        foreach ($this->items as $item) {
            $sum += $item->quantity;
        }
        return $sum;
    }

    public function getTotalSum()
    {
        $totalSum = 0;
        foreach ($this->items as $item) {
            $totalSum += $item->quantity * $item->product->price;
        }
        return $totalSum;
    }


    public function removeProduct(Product $product)
    {
        unset($this->items[$product->id]);     
    }
};
