<?php

/**
 * this class represents a product entity
 */
class Product
{

    /** Product attribute **/
    private $name, $desc, $brand, $price, $productID, $availUnits;

    function __construct($name, $desc, $brand, $price, $productID, $availUnits)
    {
        $this->name = $name;
        $this->desc = $desc;
        $this->brand = $brand;
        $this->price = $price;
        $this->productID = $productID;
        $this->availUnits = $availUnits;
    }

    function setDesc($desc)
    {
        $this->desc = $desc;
    }

    function setBrand($brand)
    {
        $this->brand = $brand;
    }

    function setPrice($price)
    {
        $this->price = $price;
    }

    function setProductID($productID)
    {
        $this->productID = $productID;
    }

    function setAvailUnits($availUnits)
    {
        $this->availUnits = $availUnits;
    }

    function getName()
    {
        return $this->name;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function getDesc()
    {
        return $this->desc;
    }

    function getBrand()
    {
        return $this->brand;
    }

    function getPrice()
    {
        return $this->price;
    }

    function getProductID()
    {
        return $this->productID;
    }

    function getAvailUnits()
    {
        return $this->availUnits;
    }
}
