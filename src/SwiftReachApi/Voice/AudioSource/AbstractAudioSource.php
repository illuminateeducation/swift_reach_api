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

    public function getAudioSourceTypes()
    {
        return array(
            self::AUDIO_SOURCE_VOICE,
            self::AUDIO_SOURCE_TTS_FIELD,
            self::AUDIO_SOURCE_TTS_TEXT,
            self::AUDIO_SOURCE_TTS_USER_DEFINED_FIELD,
        );
    }

    abstract public function getAudioType();


} 