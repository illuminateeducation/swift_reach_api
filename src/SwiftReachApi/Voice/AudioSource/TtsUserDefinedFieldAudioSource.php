<?php

namespace SwiftReachApi\Voice\AudioSource;


class TtsUserDefinedFieldAudioSource extends AbstractAudioSource
{
    protected $field_key;

    public function getAudioType()
    {
        return self::AUDIO_SOURCE_TTS_USER_DEFINED_FIELD;
    }

    public function toArray()
    {
        return array(
            "FieldKey" => $this->getFieldKey(),
            "AudioType" => $this->getAudioType()
        );
    }

    /**
     * @return mixed
     */
    public function getFieldKey()
    {
        return $this->field_key;
    }

    /**
     * @param mixed $field_key
     */
    public function setFieldKey($field_key)
    {
        $this->field_key = $field_key;
        return $this;
    }


} 