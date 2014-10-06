<?php

namespace SwiftReachApi\Email;

use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\JsonSerialize;

/**
 * Class SimpleEmailMessage
 *
 * @link http://api.v4.swiftreach.com/Help/Objects/API.V4.DataWrappers.SimpleEmail/
 */
class SimpleEmailMessage implements JsonSerialize
{

    /** @var string */
    protected $name = '';

    /** @var string */
    protected $description = '';

    /** @var string */
    protected $from_name = '';

    /** @var string */
    protected $from_address = '';

    /** @var string */
    protected $subject = '';

    /** @var string */
    protected $body = '';

    /**
     * Return the object in json
     *
     * @return string
     */
    public function toJson()
    {
        $a = array(
            "Name" => $this->getName(),
            "Description" => $this->getDescription(),
            "FromName" => $this->getFromName(),
            "FromAddress" => $this->getFromAddress(),
            "Subject" => $this->getSubject(),
            "Body" => $this->getBody(),
        );

        return json_encode($a);
    }


    /**
     * Ensure all required fields are set.
     *
     * @throws SwiftReachException
     * @return void
     */
    public function requiredFieldsSet()
    {
        $missing_fields = array_filter(
            get_object_vars($this),
            function ($a) {
                return is_null($a);
            }
        );
        if (!empty($missing_fields)) {
            $keys  = array_keys($missing_fields);
            throw new SwiftReachException(
                "The following fields cannot be blank: " . implode(", ",  $keys)
            );
        }
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }



    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return $this->from_address;
    }

    /**
     * @param string $from_address
     * @return $this
     */
    public function setFromAddress($from_address)
    {
        $this->from_address = $from_address;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->from_name;
    }

    /**
     * @param string $from_name
     * @return $this
     */
    public function setFromName($from_name)
    {
        $this->from_name = $from_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}