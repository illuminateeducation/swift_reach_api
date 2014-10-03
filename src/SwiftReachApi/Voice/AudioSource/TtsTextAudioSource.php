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
            '$type'     => "SwiftReach.Swift911.Core.Messages.Voice.AUDIO_SOURCE_TTS_TEXT, SwiftReach.Swift911.Core",
            "AudioType" => $this->getAudioType(),
            "TTSText"   => $this->getTtsText(),
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