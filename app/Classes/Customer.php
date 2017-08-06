<?php

/**
 * this class represent a Customer Entity
 */
class Customer
{

    /** Customer attributes **/
    private $firstName, $lastName, $email, $password, $phoneNumber, $birthDate, $address, $custID;

    function __construct($firstName, $lastName, $email, $password, $phoneNumber, $birthDate, $address, $custID)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
        $this->birthDate = $birthDate;
        $this->address = $address;
        $this->custID = $custID;
    }

    function setLastName($lastName)
    {
        $this->lastName = $lastName;
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

    function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    function setAddress($address)
    {
        $this->address = $address;
    }

    function getFirstName()
    {
        return $this->firstName;
    }

    function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    function getLastName()
    {
        return $this->lastName;
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

    function getBirthDate()
    {
        return $this->birthDate;
    }

    function getAddress()
    {
        return $this->address;
    }

}
