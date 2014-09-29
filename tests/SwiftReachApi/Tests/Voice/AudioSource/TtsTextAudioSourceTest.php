<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 2:39 PM
 */

namespace AudioSource;

use SwiftReachApi\Voice\AudioSource\TtsTextAudioSource;

class TtsTextAudioSourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var TtsTextAudioSource
     */
    public $text;

    public function setup()
    {
        $this->text = new TtsTextAudioSource();
    }
    public function testAudioType()
    {
        $this->assertEquals(TtsTextAudioSource::AUDIO_SOURCE_TTS_TEXT, $this->text->getAudioType());
    }

    public function testAccessTtsText()
    {
        $text = "this is a message";
        $this->assertEquals($text, $this->text->setTtsText($text)->getTtsText());
    }


    public function testToArray()
    {
        $a = array(
            '$type' => "SwiftReach.Swift911.Core.Messages.Voice.AUDIO_SOURCE_TTS_TEXT, SwiftReach.Swift911.Core",
            "TTSText"           => "This is a message",
            "AudioType"         => TtsTextAudioSource::AUDIO_SOURCE_TTS_TEXT,
        );

        $b = $this->text
           ->setTtsText($a["TTSText"])
            ->toArray();

        $this->assertTrue((array_diff_assoc($a, $b) === array_diff_assoc($b, $a)));
    }


}
 