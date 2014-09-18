<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 2:39 PM
 */

namespace AudioSource;

use SwiftReachApi\Voice\AudioSource\TtsUserDefinedFieldAudioSource;

class TtsUserDefinedFieldAudioSourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var TtsUserDefinedFieldAudioSource
     */
    public $user;

    public function setup()
    {
        $this->user = new TtsUserDefinedFieldAudioSource();
    }
    public function testAudioType()
    {
        $this->assertEquals(TtsUserDefinedFieldAudioSource::AUDIO_SOURCE_TTS_USER_DEFINED_FIELD, $this->user->getAudioType());
    }

    public function testAccessTtsText()
    {
        $field = "this is a message";
        $this->assertEquals($field, $this->user->setFieldKey($field)->getFieldKey());
    }


    public function testToArray()
    {
        $a = array(
            "FieldKey"           => "This is a message",
            "AudioType"         => TtsUserDefinedFieldAudioSource::AUDIO_SOURCE_TTS_USER_DEFINED_FIELD,
        );

        $b = $this->user
           ->setFieldKey($a["FieldKey"])
            ->toArray();

        $this->assertTrue((array_diff_assoc($a, $b) === array_diff_assoc($b, $a)));
    }


}
 