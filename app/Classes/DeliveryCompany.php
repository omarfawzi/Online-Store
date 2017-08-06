<?php

/**
 * this class represents a DeliveryCompany
 */
class DeliveryCompany
{

    /** Delivery company attributes **/
    private $name, $email, $password, $phoneNumber, $delCompanyID;

    function __construct($name, $email, $password, $phoneNumber, $delCompanyID)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
        $this->delCompanyID = $delCompanyID;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setPassword($password)
    {
        $this->password = $password;
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

    function getPassword()
    {
        return $this->password;
    }

    function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
}
