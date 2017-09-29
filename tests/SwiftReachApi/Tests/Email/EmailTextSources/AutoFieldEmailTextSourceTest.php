<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 2:39 PM
 */

namespace AudioSource;

use SwiftReachApi\Email\EmailTextSource\AbstractEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\TextEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\UserDefinedFieldEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\AutoFieldEmailTextSource;

class AutoFieldEmailTextSourceTest extends \PHPUnit_Framework_TestCase
{

    /** @var  AutoFieldEmailTextSource */
    protected $auto;

    public function setup()
    {
        $this->auto = new AutoFieldEmailTextSource();
    }

    public function testAccessors()
    {
        $this->assertEquals(AbstractEmailTextSource::EMAIL_TEXT_SOURCE_AUTO_FIELD, $this->auto->getTextType());

        $field_type = AutoFieldEmailTextSource::AUTO_FIELD_DATE;

        $this->assertEquals($field_type, $this->auto->setFieldType($field_type)->getFieldType());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidFieldType()
    {
        $this->assertFalse($this->auto->validateFieldType("invalid-type"));

        $this->auto->setFieldType("invalid-type");
    }

    public function testFieldTypes()
    {
        $this->assertTrue(is_array($this->auto->getFieldTypes()));
        $this->assertEquals(6, count($this->auto->getFieldTypes()));
    }

    public function testSetFieldTypeInConstructor()
    {
        $expected_field_type = AutoFieldEmailTextSource::AUTO_FIELD_ENTITY_NAME;
        $source = new AutoFieldEmailTextSource($expected_field_type);

        $this->assertEquals($expected_field_type, $source->getFieldType());
    }

    public function testToArray()
    {
        $this->auto->setFieldType(AutoFieldEmailTextSource::AUTO_FIELD_DATE);

        $a = $this->auto->toArray();
        $this->assertEquals($a["FieldType"], $this->auto->getFieldType());
        $this->assertEquals($a["TextType"], $this->auto->getTextType());
    }
}
