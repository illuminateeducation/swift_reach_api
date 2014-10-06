<?php

namespace SwiftReachApi\Contact;


use SwiftReachApi\Interfaces\ArraySerialize;
use SwiftReachApi\Interfaces\JsonSerialize;
use SwiftReachApi\Exceptions\SwiftReachException;

class Contact
    implements JsonSerialize, ArraySerialize
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $guid;

    /**
     * @var array
     */
    private $phones;

    /** @var  string email address */
    private $email;

    /** @var array KeyValue */
    private $user_defined_fields = array();

    public function __construct($name, $guid = '')
    {
        $this->setName($name);
        $this->phones = array();

        // if guid isn't set generate it
        if ($guid == '') {
            $this->setGuid($this->generateGuid());
        }
    }

    /**
     * generate a unique id for a
     *
     * @return string
     * @link http://php.net/manual/en/function.com-create-guid.php
     */
    public function generateGuid()
    {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        return $uuid;
    }

    /**
     * @return string Json Object serialized
     */
    public function toArray()
    {
        $a = array(
            "EntityName" => $this->getName(),
            "EntityGuid" => $this->getGuid(),
        );

        // add email
        if ($this->getEmail()) {
            $a["Email"] = $this->getEmail();
        }

        // add the phones
        if (count($this->getPhones())) {
            foreach ($this->getPhones() as $p) {
                /** @var $p ContactPhone */
                $a["Phones"][] = $p->toArray();
            }
        }

        // add the user defined fields
        if (count($this->getUserDefinedFields())) {
            foreach ($this->getUserDefinedFields() as $udf) {
                /** @var $udf KeyValue */
                $a["UserDefined"][] = $udf->toArray();
            }
        }

        return $a;
    }


    /**
     * @return string Json Object serialized
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function validateGuid($guid)
    {
        return preg_match('/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}$/', $guid) == 1;
    }

    /**
     * @param string $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        if (!$this->validateGuid($this->getGuid())) {
            throw new SwiftReachException("'" . $this->getGuid() . "' is not a valid GUID");
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $phones
     * @return $this
     */
    public function setPhones($phones)
    {
        $this->phones = array();
        foreach ($phones as $p) {
            $this->addPhone($p);
        }

        return $this;
    }

    /**
     * @param ContactPhone $phone
     * @return $this
     */
    public function addPhone(ContactPhone $phone)
    {
        $this->phones[] = $phone;
        return $this;
    }

    /**
     * @return array
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new SwiftReachException("'$email' is not a valid email address.");
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getUserDefinedFields()
    {
        return $this->user_defined_fields;
    }

    /**
     * @param array $user_defined_fields
     */
    public function setUserDefinedFields($user_defined_fields)
    {
        $this->user_defined_fields = array();
        foreach ($user_defined_fields as $udf) {
            $this->addUserDefinedField($udf);
        }

        return $this;
    }

    /**
     * @param KeyValue $user_defined_fields
     * @return $this
     */
    public function addUserDefinedField(KeyValue $user_defined_field)
    {
        $this->user_defined_fields[] = $user_defined_field;

        return $this;
    }

}