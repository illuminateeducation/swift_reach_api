<?php

namespace SwiftReachApi\Voice\AudioSource;


class TtsFieldAudioSource extends AbstractAudioSource
{
    protected $tts_text;


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