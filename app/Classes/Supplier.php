<?php

/**
 * this class represents a Supplier
 */
class Supplier
{

    /** Supplier attributes **/
    private $name, $email, $phoneNumber, $supplierID;

    function __construct($name, $email, $phoneNumber, $supplierID)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->supplierID = $supplierID;
    }

    function setEmail($email)
    {
        $this->email = $email;
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

    function getEmail()
    {
        return $this->email;
    }

    function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
}
