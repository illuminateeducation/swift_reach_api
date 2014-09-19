<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:51 AM
 */

namespace SwiftReachApi\Tests\Voice;

use SwiftReachApi\Voice\AudioSource\AbstractAudioSource;
use SwiftReachApi\Voice\AudioSource\TtsFieldAudioSource;
use SwiftReachApi\Voice\AudioSource\TtsTextAudioSource;
use SwiftReachApi\Voice\AudioSource\VoiceAudioSource;
use SwiftReachApi\Voice\MessageProfile;
use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Voice\VoiceAlertContent;

class VoiceAlertContentTest extends \PHPUnit_Framework_TestCase
{
    /** @var  VoiceAlertContent */
    public $vac;
    
    public function setup()
    {
        $this->vac = new VoiceAlertContent();
    }

    public function testAccessAudioSources()
    {
        $audio_sources = array(
            new VoiceAudioSource(),
            new TtsTextAudioSource(),
        );
        $this->assertEquals(count($audio_sources), count($this->vac->setAudioSources($audio_sources)->getAudioSources()));

        $this->vac->addAudioSource(new TtsFieldAudioSource());
        $this->assertEquals(3, count($this->vac->getAudioSources()));
    }

    public function testAccessVoiceItemType()
    {
        $type = VoiceAlertContent::ALERT_ANSWERING_MACHINE;
        $this->assertEquals($type, $this->vac->setVoiceItemType($type)->getVoiceItemType());

        $type_number = 4;
        $this->assertEquals($type_number, $this->vac->setVoiceItemType($type_number)->getVoiceItemType());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidVoiceItemType()
    {
        $type = "invalid-type";
        $this->vac->setVoiceItemType($type);
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidNumericVoiceItemType()
    {
        $type = "100";
        $this->vac->setVoiceItemType($type);
    }

    public function testCreateAudioClasses()
    {
        $voice = $this->vac->createAudioSourceByType(AbstractAudioSource::AUDIO_SOURCE_VOICE);
        $this->assertEquals(1, substr_count(get_class($voice), "VoiceAudioSource"));

        $field = $this->vac->createAudioSourceByType(AbstractAudioSource::AUDIO_SOURCE_TTS_FIELD);
        $this->assertEquals(1, substr_count(get_class($field), "TtsFieldAudioSource"));

        $text = $this->vac->createAudioSourceByType(AbstractAudioSource::AUDIO_SOURCE_TTS_TEXT);
        $this->assertEquals(1, substr_count(get_class($text), "TtsTextAudioSource"));

        $user = $this->vac->createAudioSourceByType(AbstractAudioSource::AUDIO_SOURCE_TTS_USER_DEFINED_FIELD);
        $this->assertEquals(1, substr_count(get_class($user), "TtsUserDefinedFieldAudioSource"));
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidAudioSourceType()
    {
        $type = "invalid-type";
        $this->vac->createAudioSourceByType($type);
    }
}
 