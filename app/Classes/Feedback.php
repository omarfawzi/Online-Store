<?php

/**
 * this class represents a Feedback
 */
class Feedback
{

    /** Feedback attributes **/
    private $feedbackID, $feedbackDesc;

    function __construct($feedbackID, $feedbackDesc)
    {
        $this->feedbackID = $feedbackID;
        $this->feedbackDesc = $feedbackDesc;
    }

    function setFeedbackDesc($feedbackDesc)
    {
        $this->feedbackDesc = $feedbackDesc;
    }

    function getFeedbackID()
    {
        return $this->feedbackID;
    }

    function setFeedbackID($feedbackID)
    {
        $this->feedbackID = $feedbackID;
    }

    function getFeedbackDesc()
    {
        return $this->feedbackDesc;
    }
}
