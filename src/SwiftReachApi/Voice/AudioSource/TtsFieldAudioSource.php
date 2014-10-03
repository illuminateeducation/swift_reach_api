<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 12:35 PM
 */

namespace SwiftReachApi\Voice\AudioSource;


use SwiftReachApi\Exceptions\SwiftReachException;

class TtsFieldAudioSource extends AbstractAudioSource
{

    const TTS_FIELD_ENTITYNAME = "tts_field_entityname";
    const TTS_FIELD_PHONE = "tts_field_phone";
    const TTS_FIELD_DATE = "tts_field_date";
    const TTS_FIELD_TIME = "tts_field_time";
    const TTS_FIELD_DATETIME = "tts_field_datetime";
    const TTS_FIELD_ENTITYTAG = "tts_field_entitytag";

    protected $tts_field;


    public function getAudioType()
    {
        return self::AUDIO_SOURCE_TTS_FIELD;
    }

    public function toArray()
    {
        return array(
            '$type'     => "SwiftReach.Swift911.Core.Messages.Voice.AUDIO_SOURCE_TTS_FIELD, SwiftReach.Swift911.Core",
            "TTSField"  => $this->getTtsField(),
            "AudioType" => $this->getAudioType()
        );
    }

    /**
     * @param $tts_field
     * @return bool
     * @todo finish
     */
    public function validateTtsField($tts_field)
    {
        return in_array($tts_field, $this->getTtsFields());
    }

    public function getTtsFields()
    {
        return array(
            self::TTS_FIELD_ENTITYNAME,
            self::TTS_FIELD_PHONE,
            self::TTS_FIELD_DATE,
            self::TTS_FIELD_TIME,
            self::TTS_FIELD_DATETIME,
            self::TTS_FIELD_ENTITYTAG,
        );
    }

    /**
     * @return mixed
     */
    public function getTtsField()
    {
        return $this->tts_field;
    }

    /**
     * @param mixed $tts_field
     */
    public function setTtsField($tts_field)
    {
        $this->tts_field = $tts_field;
        if (!$this->validateTtsField($this->tts_field)) {
            throw new SwiftReachException("'" . $this->tts_field . "' is not a valid TTS field");
        }

        return $this;
    }

} 