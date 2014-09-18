<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 12:32 PM
 */

namespace SwiftReachApi\Voice\AudioSource;


use SwiftReachApi\Interfaces\ArraySerialize;

abstract class AbstractAudioSource
implements ArraySerialize
{
    const AUDIO_SOURCE_VOICE                    = "audio_source_voice";
    const AUDIO_SOURCE_TTS_FIELD                = "audio_source_tts_field";
    const AUDIO_SOURCE_TTS_TEXT                 = "audio_source_tts_text";
    const AUDIO_SOURCE_TTS_USER_DEFINED_FIELD   = "audio_source_tts_user_defined_field";

    protected $audio_type;

    public function validateAudioType($audio_type)
    {
        return in_array($audio_type, $this->getAudioSourceTypes());
    }

    public function getAudioSourceTypes()
    {
        return array(
            self::AUDIO_SOURCE_VOICE,
            self::AUDIO_SOURCE_TTS_FIELD,
            self::AUDIO_SOURCE_TTS_TEXT,
            self::AUDIO_SOURCE_TTS_USER_DEFINED_FIELD,
        );
    }


    /**
     * @return mixed
     */
    public function getAudioType()
    {
        return $this->audio_type;
    }

    /**
     * @param mixed $audio_type
     */
    public function setAudioType($audio_type)
    {
        $this->audio_type = $audio_type;
        if(!$this->validateAudioType($this->audio_type)){
            throw new SwiftReachException("'".$this->audio_type."' is not a valid Audio Type");
        }
        return $this;
    }
} 