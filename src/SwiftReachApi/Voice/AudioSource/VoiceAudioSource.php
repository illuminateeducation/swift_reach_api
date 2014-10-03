<?php

namespace SwiftReachApi\Voice\AudioSource;


class VoiceAudioSource extends AbstractAudioSource
{
    protected $voice_code;
    protected $content = '';
    protected $auto_generate_voice = true;
    protected $file_version = 1;

    public function getAudioType()
    {
        return self::AUDIO_SOURCE_VOICE;
    }

    public function toArray()
    {
        return array(
            '$type'             => "SwiftReach.Swift911.Core.Messages.Voice.AUDIO_SOURCE_VOICE, SwiftReach.Swift911.Core",
            "Content"           => $this->getContent(),
            "AutoGenerateVoice" => $this->isAutoGenerateVoice(),
            "FileVersion"       => $this->getFileVersion(),
            "AudioType"         => $this->getAudioType(),
        );
    }


    /**
     * @return boolean
     */
    public function isAutoGenerateVoice()
    {
        return $this->auto_generate_voice;
    }

    /**
     * @param boolean $auto_generate_voice
     */
    public function setAutoGenerateVoice($auto_generate_voice)
    {
        $this->auto_generate_voice = $auto_generate_voice;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileVersion()
    {
        return $this->file_version;
    }

    /**
     * @param mixed $file_version
     */
    public function setFileVersion($file_version)
    {
        $this->file_version = $file_version;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoiceCode()
    {
        return $this->voice_code;
    }

    /**
     * @param mixed $voice_code
     */
    public function setVoiceCode($voice_code)
    {
        $this->voice_code = $voice_code;

        return $this;
    }


} 