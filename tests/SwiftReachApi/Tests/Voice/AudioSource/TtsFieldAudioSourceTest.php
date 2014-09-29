<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 2:39 PM
 */

namespace AudioSource;

use SwiftReachApi\Voice\AudioSource\TtsFieldAudioSource;

class TtsFieldAudioSourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var TtsFieldAudioSource
     */
    public $field;

    public function setup()
    {
        $this->field = new TtsFieldAudioSource();
    }
    public function testAudioType()
    {
        $this->assertEquals(TtsFieldAudioSource::AUDIO_SOURCE_TTS_FIELD, $this->field->getAudioType());
    }

    public function testAccessTtsField()
    {
        $field = TtsFieldAudioSource::TTS_FIELD_DATETIME;
        $this->assertEquals($field, $this->field->setTtsField($field)->getTtsField());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidSetTtsField()
    {
        $field = "invalid-field";
        $this->field->setTtsField($field);
    }


    public function testToArray()
    {
        $a = array(
            '$type'         => "SwiftReach.Swift911.Core.Messages.Voice.AUDIO_SOURCE_TTS_FIELD, SwiftReach.Swift911.Core",
            "TTSField"      => TtsFieldAudioSource::TTS_FIELD_TIME,
            "AudioType"     => TtsFieldAudioSource::AUDIO_SOURCE_TTS_FIELD,
        );

        $b = $this->field
           ->setTtsField($a["TTSField"])
            ->toArray();

        $this->assertTrue((array_diff_assoc($a, $b) === array_diff_assoc($b, $a)));
    }


}
 