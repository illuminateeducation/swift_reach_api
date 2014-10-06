<?php


namespace SwiftReachApi\Tests\Contact;


use SwiftReachApi\Contact\ContactPhone;

class ContactPhoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContactPhone
     */
    public $cp;

    public function setup()
    {
        $this->cp = new ContactPhone("5551236478", "home");
    }

    public function testAccessAnsDetection()
    {
        $this->cp->setAnsDetectionOverride(2);
        $this->assertEquals(2, $this->cp->getAnsDetectionOverride());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNonNumericAnsDetectionOverride()
    {
        $this->cp->setAnsDetectionOverride("asd");
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNegativeAnsDetectionOverride()
    {
        $this->cp->setAnsDetectionOverride(-1);
    }
    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testOverTwoAnsDetectionOverride()
    {
        $this->cp->setAnsDetectionOverride(4);
    }

    public function testAccessExtention()
    {
        $this->cp->setExtention(2);
        $this->assertEquals(2, $this->cp->getExtention());
    }

    public function testAccessPhoneType()
    {
        $this->cp->setPhoneType(0);
        $this->assertEquals(0, $this->cp->getPhoneType());
    }

    public function testAccessOptinSms()
    {
        $this->cp->setOptinSms(false);
        $this->assertFalse($this->cp->getOptinSms());

        $this->cp->setOptinSms(true);
        $this->assertTrue($this->cp->getOptinSms());
    }

    public function testAccessPhone()
    {
        $test_phone = "5551236478";
        $this->cp->setPhone($test_phone);
        $this->assertEquals($test_phone, $this->cp->getPhone());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNonNumericPhone()
    {
        $this->cp->setPhone("555-123-6478");
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testTooShortPhone()
    {
        $this->cp->setPhone("5551234");
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testTooLongPhone()
    {
        $this->cp->setPhone("5551236958741");
    }
}
 