<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 2:39 PM
 */

namespace AudioSource;

use SwiftReachApi\Email\EmailTextSource\AbstractEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\UserDefinedFieldEmailTextSource;


class UserDefinedFieldEmailTextSourceTest extends \PHPUnit_Framework_TestCase
{

    /** @var  UserDefinedFieldEmailTextSource */
    protected $user;

    public function setup()
    {
        $this->user = new UserDefinedFieldEmailTextSource();
    }

    public function testAccessors()
    {
        $this->assertEquals(AbstractEmailTextSource::EMAIL_TEXT_SOURCE_USER_DEFINED_FIELD, $this->user->getTextType());
        $field = "field-name";
        $this->assertEquals($field, $this->user->setFieldKey($field)->getFieldKey());
    }


    public function testToArray()
    {
        $this->user->setFieldKey("field-key");

        $a = $this->user->toArray();
        $this->assertEquals($a["FieldKey"], $this->user->getFieldKey());
        $this->assertEquals($a["TextType"], $this->user->getTextType());
    }
}
 