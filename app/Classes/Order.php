<?php

/**
 * this class represents an order
 */
class Order
{

    /** Order attributes **/
    private $region, $address, $payment, $orderID;

    function __construct($region, $address, $payment, $orderID)
    {
        $this->region = $region;
        $this->address = $address;
        $this->payment = $payment;
        $this->orderID = $orderID;
    }

    function setAddress($address)
    {
        $this->address = $address;
    }

    function setPayment($payment)
    {
        $this->payment = $payment;
    }

    function getRegion()
    {
        return $this->region;
    }

    function setRegion($region)
    {
        $this->region = $region;
    }

    function getAddress()
    {
        return $this->address;
    }

    function getPayment()
    {
        return $this->payment;
    }
}
