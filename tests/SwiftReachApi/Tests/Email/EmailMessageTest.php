<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:51 AM
 */

namespace SwiftReachApi\Tests\Email;

use SwiftReachApi\Email\EmailMessage;
use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Email\EmailContent;

class EmailMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var  EmailMessage */
    public $em;
    
    public function setup()
    {
        $this->em = new EmailMessage();
    }

    public function testAccessors()
    {
        //vals
        $a = array(
            "Name"         => "test message name",
            "Description"  => "shor description",
            "FromName"      => "test name",
            "FromAddress"   => "test@test.com",
            "EmailCode"           => 123456789,
            "CreatedByUser"       => "test_user",
            "ChangedByUser"       => "test_user",
            "DefaultSpokenLanguage"       => "Spanish",
            "LastUsed"            => "0001-01-01T00:00:00",
            "CreateStamp"         => "0001-01-01T00:00:00",
            "ChangeStamp"         => "0001-01-01T00:00:00",
        );

        foreach($a as $func => $val){
            $get = "get".$func;
            $set = "set".$func;
            $this->assertEquals($val, $this->em->$set($val)->$get());
        }

        //booleans
        $a = array(
            "DeleteLocked"    => true
        );
        foreach($a as $func => $val){
            $is = "is".$func;
            $set = "set".$func;
            $this->assertEquals($val, $this->em->$set($val)->$is());
        }
    }

    public function testAcceessVisiblity()
    {
        $this->em->setVisibility(EmailMessage::VISIBILITY_TYPE_HIDDEN);
        $this->assertEquals(EmailMessage::VISIBILITY_TYPE_HIDDEN, $this->em->getVisibility());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidVisiblity()
    {
        $this->em->setVisibility('invalid-visibility');
    }

    public function testPopulateFromArray()
    {

        $email_json = json_decode(file_get_contents(
                                      __DIR__ . "/../../Data/Email/email_message.json"
                                  ), true);
        $em = new EmailMessage();
        $em->populateFromArray($email_json);

        $special_functions = array(
            "DeleteLocked"                    => "isDeleteLocked",
            "Content"                       => "skipContentProfile", // this is tested elsewhere
        );

        foreach ($email_json as $key => $value) {
            if (in_array($key, array_keys($special_functions))) {
                $get_method = $special_functions[$key];
            } else {
                $get_method = "get" . $key;
            }

            if (method_exists($em, $get_method)) {
                $this->assertEquals($em->$get_method($value), $value);
            }
        }
    }

    public function testAccessContentProfile()
    {
        $ec = new EmailContent();
        $this->em->setContent(array($ec));
        $contents = $this->em->getContent();
        $this->assertEquals(1, count($contents));
        $this->assertEquals(1, substr_count(get_class(array_pop($contents)),"EmailContent"));
    }

    /** @expectedException SwiftReachApi\Exceptions\SwiftReachException */
    public function testMissingFieldsRequired()
    {
        $this->em
            ->setName('email_message')
            ->setDescription('email description')
            ->setFromAddress('example@example.com')
            ->setFromName('');

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
        $email_json = json_decode(file_get_contents(
                                      __DIR__ . "/../../Data/Email/email_message.json"
                                  ), true);
        $em = new EmailMessage();
        $em->populateFromArray($email_json);

        $a = array(
            "Name", "Description", "FromName", "FromAddress", "DefaultSpokenLanguage", "Visibility"
        );

        $email_array = json_decode($em->toJson(), true);

        foreach($a as $field){
            $this->assertEquals($email_json[$field], $email_array[$field]);
        }

        $this->assertEquals($email_json["DeleteLocked"], $em->isDeleteLocked());
    }

}
