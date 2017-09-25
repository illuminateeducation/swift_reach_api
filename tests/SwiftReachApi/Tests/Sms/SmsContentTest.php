<?php
/** @filesource */

namespace SwiftReachApi\Tests\Sms;


use SwiftReachApi\Sms\SmsContent;

class SmsContentTest extends \PHPUnit_Framework_TestCase
{
    /** @var  SmsContent */
    private $sms;

    public function setup()
    {
        $this->sms = new SmsContent();
    }

    public function testPopulateFromArray()
    {

        $sms_json  = json_decode(file_get_contents(__DIR__ . "/../../Data/Sms/sms_message.json"), true);
        $this->sms = new SmsContent();
        $this->sms->populateFromArray($sms_json['Body'][0]);

        $special_functions = [
            "DeleteLocked" => "isDeleteLocked",
            "Body"         => "skipContentProfile", // this is tested elsewhere
        ];

        foreach ($sms_json['Body'][0] as $key => $value) {
            if (in_array($key, array_keys($special_functions))) {
                $get_method = $special_functions[$key];
            } else {
                $get_method = "get" . $key;
            }

            if (method_exists($this->sms, $get_method)) {
                $this->assertEquals($this->sms->$get_method($value), $value);
            }
        }
    }

    public function testBodyAccessors()
    {
        $sms_json  = json_decode(file_get_contents(__DIR__ . "/../../Data/Sms/sms_message.json"), true);
        $this->sms = new SmsContent();
        $this->sms->populateFromArray($sms_json['Body'][0]);

        $this->sms->setBody([]);
        $this->assertCount(0, $this->sms->getBody());
    }

    public function testToArray()
    {
        $expected_subject = "subject";
        $expected_spoken_lang = 'english';
        $this->sms
            ->setSubject($expected_subject)
            ->setSpokenLanguage($expected_spoken_lang)
            ->setBody([new SmsContent()]);


        $actual = $this->sms->toArray();


        $this->assertEquals($expected_subject, $actual['Subject']);
        $this->assertEquals($expected_spoken_lang, $actual['SpokenLanguage']);
        $this->assertCount(1, $actual['Body']);

    }
}
