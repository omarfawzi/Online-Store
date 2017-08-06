<?php

/**
 * this class represents a ShoppingCard
 */
class ShoppingCart
{

    /** Shopping cart attributes **/
    private $cartID, $totalPayment;

    function __construct($cartID, $totalPayment)
    {
        $this->cartID = $cartID;
        $this->totalPayment = $totalPayment;
    }

    function setTotalPayment($totalPayment)
    {
        $this->totalPayment = $totalPayment;
    }

    function getCartID()
    {
        return $this->cartID;
    }

    function setCartID($cartID)
    {
        $this->cartID = $cartID;
    }

    function getTotalPayment()
    {
        return $this->totalPayment;
    }
}
