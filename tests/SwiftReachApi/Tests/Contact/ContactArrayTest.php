<?php

namespace SwiftReachApi\Tests\Contact;


use SwiftReachApi\Contact\Contact;

use SwiftReachApi\Contact\ContactPhone;
use SwiftReachApi\Contact\ContactArray;

class ContactArrayTest extends \PHPUnit_Framework_TestCase {

    /** @var  ContactArray */
    public $ca;

    /** @var  Contact */
    public $contact1;

    /** @var  Contact */
    public $contact2;

    public function setup()
    {
        $this->ca = new ContactArray();

        $this->contact1 = new Contact("Test Tester");
        $contact1_phone = new ContactPhone("5555555555","home");
        $this->contact1->setPhones(array($contact1_phone));


        $this->contact2 = new Contact("two phone test");
        $contact2_phone1 = new ContactPhone("5555555555","home");
        $contact2_phone2 = new ContactPhone("5555555555","home");
        $this->contact2->setPhones(array($contact2_phone1, $contact2_phone2));
    }


    public function testAccessContacts()
    {
        $this->ca->setContacts(array($this->contact1));
        $this->assertEquals(1, count($this->ca->getContacts()));

        $this->ca->addContact($this->contact2);
        $this->assertEquals(2, count($this->ca->getContacts()));

        // make sure it clears the original array
        $this->ca->setContacts(array($this->contact1));
        $this->assertEquals(1, count($this->ca->getContacts()));
    }

    /**
     * @expectedException \Exception
     */
    public function testNonVoiceContactContactAdd()
    {
        $vc = array("test");
        $this->ca->setContacts(array($vc));
    }


    public function testToJson()
    {
        $a = array(
            $this->contact1->toArray(),
            $this->contact2->toArray(),
        );
        $this->ca->setContacts(array($this->contact1, $this->contact2));
        $this->assertEquals(json_encode($a), $this->ca->toJson());
    }
}

