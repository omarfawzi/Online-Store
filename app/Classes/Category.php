<?php

class Category
{

    /** Category attributes **/
    private $name, $desc, $catID;
    private $products;

    function __construct($name, $desc, $catID)
    {
        $this->name = $name;
        $this->desc = $desc;
        $this->catID = $catID;
        $this->products = Array();
    }

    function addProduct($product)
    { //this method adds a new product to the products array
        $this->products[] = $product;
    }

    function &getProducts()
    {
        return $this->products;
    }

    function setDesc($desc)
    {
        $this->desc = $desc;
    }

    function setCatID($catID)
    {
        $this->catID = $catID;
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

    function getCatID()
    {
        return $this->catID;
    }
}
