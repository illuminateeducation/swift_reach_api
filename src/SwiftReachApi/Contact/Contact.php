<?php

namespace Ied\SwiftReachApi\Contact;

use Ied\SwiftReachApi\Interfaces\ArraySerialize;
use Ied\SwiftReachApi\Interfaces\JsonSerialize;
use Ied\SwiftReachApi\Exceptions\SwiftReachException;

class Contact
    implements JsonSerialize, ArraySerialize
{
    /** @var string */
    private $name;

    /** @var string */
    private $guid;

    /** @var array */
    private $phones;

    /** @var string */
    private $email;

    /** @var string */
    private $sms_phone;

    /** @var int */
    private $sms_network = 0;

    /** @var array KeyValue */
    private $user_defined_fields = [];

    /** @var string */
    private $spoken_language = 'English';

    public function __construct($name, $guid = '')
    {
        $this->setName($name);
        $this->phones = [];

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
        mt_srand((double) microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid   = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        return $uuid;
    }

    /**
     * @return array Json Object serialized
     */
    public function toArray()
    {
        $a = [
            "EntityName" => $this->getName(),
            "EntityGuid" => $this->getGuid(),
        ];

        // add email
        if ($this->getEmail()) {
            $a["Email"] = $this->getEmail();
        }

        if ($this->getSmsPhone()) {
            $a["SMSPhone"]   = $this->getSmsPhone();
            $a["SMSNetwork"] = $this->getSmsNetwork();
        }

        // add the phones
        if (count($this->getPhones())) {
            foreach ($this->getPhones() as $p) {
                /** @var $p ContactPhone */
                $a["Phones"][] = $p->toArray();
            }
        }

        $a['SpokenLanguage'] = $this->getSpokenLanguage();

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

    /**
     * @param $guid
     *
     * @return bool
     */
    public function validateGuid($guid)
    {
        return preg_match('/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}$/', $guid) == 1;
    }

    /**
     * @param string $guid
     *
     * @return Contact
     * @throws SwiftReachException
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
     *
     * @return Contact
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
     *
     * @return Contact
     */
    public function setPhones($phones)
    {
        $this->phones = [];
        foreach ($phones as $p) {
            $this->addPhone($p);
        }

        return $this;
    }

    /**
     * @param ContactPhone $phone
     *
     * @return Contact
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
     *
     * @return Contact
     * @throws SwiftReachException
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
     * @return string
     */
    public function getSmsPhone()
    {
        return $this->sms_phone;
    }

    /**
     * @param string $sms_phone
     *
     * @return Contact
     */
    public function setSmsPhone($sms_phone)
    {
        $this->sms_phone = $sms_phone;

        return $this;
    }

    /**
     * @return int
     */
    public function getSmsNetwork()
    {
        return $this->sms_network;
    }

    /**
     * @param int $sms_network
     *
     * @return Contact
     */
    public function setSmsNetwork($sms_network)
    {
        $this->sms_network = $sms_network;

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
     *
     * @return Contact
     */
    public function setUserDefinedFields($user_defined_fields)
    {
        $this->user_defined_fields = [];
        foreach ($user_defined_fields as $udf) {
            $this->addUserDefinedField($udf);
        }

        return $this;
    }

    /**
     * @param KeyValue $user_defined_fields
     *
     * @return Contact
     */
    public function addUserDefinedField(KeyValue $user_defined_field)
    {
        $this->user_defined_fields[] = $user_defined_field;

        return $this;
    }

    /**
     * @return string
     */
    public function getSpokenLanguage()
    {
        return $this->spoken_language;
    }

    /**
     * @param string $spoken_langauge
     *
     * @return Contact
     */
    public function setSpokenLanguage($spoken_language)
    {
        $this->spoken_language = $spoken_language;

        return $this;
    }

}
