<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 2:39 PM
 */

namespace AudioSource;

use SwiftReachApi\Voice\AudioSource\VoiceAudioSource;

class VoiceAudioSourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var VoiceAudioSource
     */
    public $vast;

    public function setup()
    {
        $this->vast = new VoiceAudioSource();
    }
    public function testAccessVoiceCode()
    {
        $voice_code = 123456789;
        $this->assertEquals($voice_code, $this->vast->setVoiceCode($voice_code)->getVoiceCode());
    }

    public function testAccessAutoGenerateVoice()
    {
        $generate_voice = true;
        $this->assertTrue($this->vast->setAutoGenerateVoice($generate_voice)->isAutoGenerateVoice());
    }
    public function testAccessContent()
    {
        $content = 123456789;
        $this->assertEquals($content, $this->vast->setContent($content)->getContent());
    }

    public function testAccessFileVersion()
    {
        $file_version = 123456789;
        $this->assertEquals($file_version, $this->vast->setFileVersion($file_version)->getFileVersion());
    }

    public function testToArray()
    {
        $a = array(
            "VoiceCode"         => "123456789",
            "Content"           => "this is the message",
            "AutoGenerateVoice" => true,
            "FileVersion"       => 5,
            "AudioType"         => VoiceAudioSource::AUDIO_SOURCE_VOICE,
        );

        $b = $this->vast
            ->setVoiceCode($a["VoiceCode"])
            ->setContent($a["Content"])
            ->setAutoGenerateVoice($a["AutoGenerateVoice"])
            ->setFileVersion($a["FileVersion"])
            ->toArray();

        $this->assertTrue((array_diff_assoc($a, $b) === array_diff_assoc($b, $a)));
    }


}
 