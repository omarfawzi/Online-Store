<?php

/**
 * this class represents a Complain
 */
class Complain
{

    /** Complain attributes **/
    private $complainID, $complainDesc;

    function __construct($complainID, $complainDesc)
    {
        $this->complainID = $complainID;
        $this->complainDesc = $complainDesc;
    }

    function setComplainDesc($complainDesc)
    {
        $this->complainDesc = $complainDesc;
    }

    function getComplainID()
    {
        return $this->complainID;
    }

    function setComplainID($complainID)
    {
        $this->complainID = $complainID;
    }

    function getComplainDesc()
    {
        return $this->complainDesc;
    }
}
