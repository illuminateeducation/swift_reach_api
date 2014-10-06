<?php

namespace SwiftReachApi\Contact;


use SwiftReachApi\Interfaces\ArraySerialize;

class KeyValue
    implements ArraySerialize
{
    /** @var  string */
    protected $key = '';
    /** @var  string */

    protected $value = '';

    function __construct($key = '', $value = '')
    {
        $this->key = $key;
        $this->value = $value;
    }


    public function toArray()
    {
        return array(
            "Key"   => $this->getKey(),
            "Value" => $this->getValue(),
        );
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * return this;
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }


}