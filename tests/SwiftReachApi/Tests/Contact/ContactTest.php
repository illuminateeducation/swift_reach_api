<?php

namespace SwiftReachApi\Tests;


use SwiftReachApi\Contact\KeyValue;
use SwiftReachApi\Contact\Contact;
use SwiftReachApi\Contact\ContactPhone;

class VoiceContactTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Contact
     */
    public $c;

    /**
     * @var ContactPhone
     */
    public $phone1;

    /**
     * @var ContactPhone
     */
    public $phone2;

    public function setup()
    {
        $this->c = new Contact("test");
        $this->c->setEmail("test@test.com");

        $this->phone1 = new ContactPhone("5551237890", "home");
        $this->phone2 = new ContactPhone("5553021587", "home");
    }

    public function testAccessName()
    {
        $test_name = 'testname';
        $this->c->setName($test_name);
        $this->assertEquals($test_name, $this->c->getName());
    }

    public function testAccessGuid()
    {
        $guid = $this->c->generateGuid();

        $this->c->setGuid($guid);
        $this->assertEquals($guid, $this->c->getGuid());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidGuid()
    {
        $this->c->setGuid("bad-guid");
    }

    public function testToJson()
    {
        $kv = new KeyValue("key", "value");
        $a = array(
            "EntityName"  => $this->c->getName(),
            "EntityGuid"  => $this->c->getGuid(),
            "Email"       => $this->c->getEmail(),
            "Phones"      => array($this->phone1->toArray()),
            "UserDefined" => array($kv->toArray())
        );
        $this->c->addPhone($this->phone1);
        $this->c->setUserDefinedFields(array($kv));
        $this->assertNotNull(json_decode($this->c->toJson()));
    }

    public function testAccessPhones()
    {
        $this->c->setPhones(array($this->phone1));
        $this->assertEquals(1, count($this->c->getPhones()));

        $this->c->addPhone($this->phone2);
        $this->assertEquals(2, count($this->c->getPhones()));

        // make sure it clears the original array
        $this->c->setPhones(array($this->phone1));
        $this->assertEquals(1, count($this->c->getPhones()));
    }

    public function testAccessEmail()
    {
        $email = "test@test.com";
        $this->assertEquals($email, $this->c->setEmail($email)->getEmail());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidEmail()
    {
        $email = "testtest.com";
        $this->c->setEmail($email);
    }
}
 