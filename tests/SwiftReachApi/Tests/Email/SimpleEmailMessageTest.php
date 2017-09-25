<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:51 AM
 */

namespace SwiftReachApi\Tests\Email;

use SwiftReachApi\Email\SimpleEmailMessage;

class SimpleEmailMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var  SimpleEmailMessage */
    public $em;

    public function setup()
    {
        $this->em = new SimpleEmailMessage();
    }

    public function testAccessors()
    {
        //vals
        $a = [
            "Name"        => "test message name",
            "Description" => "shor description",
            "FromName"    => "test name",
            "FromAddress" => "test@test.com",
            "Subject"     => 'Test Subject',
            "Body"        => "this is the email body",
        ];

        foreach ($a as $func => $val) {
            $get = "get" . $func;
            $set = "set" . $func;
            $this->assertEquals($val, $this->em->$set($val)->$get());
        }
    }


    /** @expectedException SwiftReachApi\Exceptions\SwiftReachException */
    public function testMissingFieldsRequired()
    {
        $this->em
            ->setName('email_message')
            ->setDescription('email description')
            ->setFromAddress('example@example.com')
            ->setFromName(null);

        $this->em->requiredFieldsSet();
    }

    public function testAllFieldsRequired()
    {
        $this->em
            ->setName('email_message')
            ->setDescription('email description')
            ->setFromAddress('example@example.com')
            ->setFromName('John Smith');

        $this->em->requiredFieldsSet();
    }

    public function testToJson()
    {
        $email_json = json_decode(file_get_contents(__DIR__ . "/../../Data/Email/simple_email_message.json"), true);
        $em         = new SimpleEmailMessage();

        foreach ($email_json as $key => $value) {
            $setMethod = 'set' . $key;
            $em->$setMethod($value);
        }

        $email_array = json_decode($em->toJson(), true);

        $this->assertCount(0, array_diff_assoc($email_json, $email_array));
    }
}
