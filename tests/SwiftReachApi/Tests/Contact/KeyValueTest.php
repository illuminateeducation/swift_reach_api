<?php

namespace SwiftReachApi\Tests\Contact;


use SwiftReachApi\Contact\KeyValue;
use SwiftReachApi\Contact\ContactPhone;

class KeyValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KeyValue
     */
    public $kv;

    public function setup()
    {
        $this->kv = new KeyValue();
    }

    public function testConstruct()
    {
        $kv = new KeyValue();
        $this->assertEquals('', $kv->getKey());
        $this->assertEquals('', $kv->getValue());

        $key = "test";
        $value = " is this set";
        $kv = new KeyValue($key, $value);
        $this->assertEquals($key, $kv->getKey());
        $this->assertEquals($value, $kv->getValue());
    }

    public function testAccessKey()
    {
        $key = "key";
        $this->assertEquals($key, $this->kv->setKey($key)->getKey());
    }
    public function testAccessValue()
    {
        $value = "value";
        $this->assertEquals($value, $this->kv->setValue($value)->getValue());
    }

    public function testToArray()
    {
        $a = array(
            "Key" => "the-key",
            "Value" => "the value",
        );
        $kv = new KeyValue($a["Key"], $a["Value"]);
        $b = $kv->toArray();
        $this->assertTrue((array_diff_assoc($a, $b) === array_diff_assoc($b, $a)));
    }

}
 