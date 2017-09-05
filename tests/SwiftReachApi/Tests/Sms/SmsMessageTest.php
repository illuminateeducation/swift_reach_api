<?php
/** @filesource */

namespace SwiftReachApi\Tests\Sms;


use SwiftReachApi\Sms\SmsContent;
use SwiftReachApi\Sms\SmsMessage;

class SmsMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var  SmsMessage */
    private $sms;

    public function setup()
    {
        $this->sms = new SmsMessage();
    }
    public function testPopulateFromArray()
    {

        $email_json = json_decode(file_get_contents(__DIR__ . "/../../Data/Sms/sms_message.json"), true);
        $sms         = new SmsMessage();
        $sms->populateFromArray($email_json);

        $special_functions = [
            "DeleteLocked" => "isDeleteLocked",
            "Body"      => "skipContentProfile", // this is tested elsewhere
        ];

        foreach ($email_json as $key => $value) {
            if (in_array($key, array_keys($special_functions))) {
                $get_method = $special_functions[$key];
            } else {
                $get_method = "get" . $key;
            }

            if (method_exists($sms, $get_method)) {
                $this->assertEquals($sms->$get_method($value), $value);
            }
        }
    }

    /** @expectedException SwiftReachApi\Exceptions\SwiftReachException */
    public function testMissingFieldsRequired()
    {
        $this->sms
            ->setName('sms message')
            ->setDescription('sms description')
            ->setFromAddress('1234567890')
            ->setFromName('john smith')
            ->setReplyType('');

        $this->sms->requiredFieldsSet();
    }

    public function testAllFieldsRequired()
    {
        $this->sms
            ->setName('sms message')
            ->setDescription('sms description')
            ->setFromAddress('1234567890')
            ->setFromName('john smith')
            ->setReplyType('text')
            ->setBody([new SmsContent()]);

        $this->sms->requiredFieldsSet();
    }
}
