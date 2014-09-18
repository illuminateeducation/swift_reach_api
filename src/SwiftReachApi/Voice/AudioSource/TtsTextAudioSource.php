<?php

namespace SwiftReachApi\Voice\AudioSource;


class TtsTextAudioSource extends AbstractAudioSource
{
    protected $tts_text;

    public function getAudioType()
    {
        return self::AUDIO_SOURCE_TTS_TEXT;
    }

    public function toArray()
    {
        return array(
            "TTSText" => $this->getTtsText(),
            "AudioType" => $this->getAudioType()
        );
    }

    /**
     * @return mixed
     */
    public function getTtsText()
    {
        return $this->tts_text;
    }

    /**
     * @param mixed $tts_text
     */
    public function setTtsText($tts_text)
    {
        $this->tts_text = $tts_text;
        return $this;
    }

} 