<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:51 AM
 */

namespace SwiftReachApi\Tests\Voice;

use SwiftReachApi\Voice\MessageProfile;
use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Voice\VoiceAlertContent;
use SwiftReachApi\Voice\VoiceAlertProfile;

class VoiceAlertProfileTest extends \PHPUnit_Framework_TestCase
{
    /** @var  VoiceAlertProfile */
    public $vap;
    
    public function setup()
    {
        $this->vap = new VoiceAlertProfile();
    }

    public function testAccessTtyText()
    {
        $text = "test";
        $this->assertEquals($text, $this->vap->setTtyText($text)->getTtyText());
    }
    public function testAccessSpokenLanguage()
    {
        $language = "Spanish";
        $this->assertEquals($language, $this->vap->setSpokenLanguage($language)->getSpokenLanguage());
    }

    public function testAccessVoiceItems()
    {
        $voice_items = array(
            new VoiceAlertContent(),
            new VoiceAlertContent(),
        );
        $this->assertEquals(count($voice_items), count($this->vap->setVoiceItems($voice_items)->getVoiceItems()));

        $this->vap->addVoiceAlertContent(new VoiceAlertContent());
        $this->assertEquals(3, count($this->vap->getVoiceItems()));
    }

    public function testPopulateFromArray()
    {
        $a = json_decode(file_get_contents(__DIR__."/../../Data/voice_alert_profile.json"), true);
        $this->vap->populateFromArray($a);
        $this->assertEquals($a["SpokenLanguage"], $this->vap->getSpokenLanguage());
        $this->assertEquals($a["TTY_Text"], $this->vap->getTtyText());
        $this->assertEquals(count($a["VoiceItem"]), count($this->vap->getVoiceItems()));
    }

    public function testToArray()
    {
        $a = json_decode(file_get_contents(__DIR__."/../../Data/voice_alert_profile.json"), true);
        $this->vap->populateFromArray($a);
        $x = $this->vap->toArray();

        $this->assertEquals($a["SpokenLanguage"], $x["SpokenLanguage"]);
        $this->assertEquals($a["TTY_Text"], $x["TTY_Text"]);
        $this->assertEquals(count($a["VoiceItem"]), count($x["VoiceItem"]));
    }

}
 