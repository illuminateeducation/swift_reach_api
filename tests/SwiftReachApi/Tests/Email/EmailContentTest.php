<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:51 AM
 */

namespace SwiftReachApi\Tests\Email;

use SwiftReachApi\Email\EmailContent;
use SwiftReachApi\Email\EmailTextSource\AbstractEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\AutoFieldEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\TextEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\UserDefinedFieldEmailTextSource;
use SwiftReachApi\Exceptions\SwiftReachException;


class EmailContentTest extends \PHPUnit_Framework_TestCase
{
    /** @var  EmailContent */
    public $ec;
    
    public function setup()
    {
        $this->ec = new EmailContent();
    }

    public function testAccessSubject()
    {
        $subject = "test";
        $this->assertEquals($subject, $this->ec->setSubject($subject)->getSubject());
    }
    public function testAccessSpokenLanguage()
    {
        $language = "Spanish";
        $this->assertEquals($language, $this->ec->setSpokenLanguage($language)->getSpokenLanguage());
    }

    public function testAccessBody()
    {
        $bodies = array(
            new TextEmailTextSource(),
            new UserDefinedFieldEmailTextSource(),
        );
        $this->assertEquals(count($bodies), count($this->ec->setBody($bodies)->getBody()));

        $this->ec->addBody(new AutoFieldEmailTextSource());
        $this->assertEquals(3, count($this->ec->getBody()));
    }

    public function testPopulateFromArray()
    {
        $a = json_decode(file_get_contents(__DIR__."/../../Data/Email/email_content.json"), true);
        $this->ec->populateFromArray($a);
        $this->assertEquals($a["SpokenLanguage"], $this->ec->getSpokenLanguage());
        $this->assertEquals($a["Subject"], $this->ec->getSubject());
        $this->assertEquals(count($a["Body"]), count($this->ec->getBody()));
    }

    public function testToArray()
    {
        $a = json_decode(file_get_contents(__DIR__."/../../Data/Email/email_content.json"), true);
        $this->ec->populateFromArray($a);
        $x = $this->ec->toArray();

        $this->assertEquals($a["SpokenLanguage"], $x["SpokenLanguage"]);
        $this->assertEquals($a["Subject"], $x["Subject"]);
        $this->assertEquals(count($a["Body"]), count($x["Body"]));
    }

    public function testCreateEmailTextSourceClasses()
    {

        $field = $this->ec->createEmailTextSourceByType(AbstractEmailTextSource::EMAIL_TEXT_SOURCE_AUTO_FIELD);
        $this->assertEquals(1, substr_count(get_class($field), "AutoFieldEmailTextSource"));

        $text = $this->ec->createEmailTextSourceByType(AbstractEmailTextSource::EMAIL_TEXT_SOURCE_TEXT);
        $this->assertEquals(1, substr_count(get_class($text), "TextEmailTextSource"));

        $user = $this->ec->createEmailTextSourceByType(AbstractEmailTextSource::EMAIL_TEXT_SOURCE_USER_DEFINED_FIELD);
        $this->assertEquals(1, substr_count(get_class($user), "UserDefinedFieldEmailTextSource"));
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testCreateInvalidEmailTextSourceClasses()
    {
        $this->ec->createEmailTextSourceByType("invalid type");
    }

}
 