<?php

/**
 * this class represents a DeliveryMan
 */
class DeliveryMan
{

    /** Delivery man attributes **/
    private $name, $phoneNumber, $delManID;

    function __construct($name, $phoneNumber, $delManID)
    {
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->delManID = $delManID;
    }

    function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    function getName()
    {
        return $this->name;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
}
