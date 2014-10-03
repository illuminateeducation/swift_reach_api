<?php

namespace SwiftReachApi\Email\EmailTextSource;


class TextEmailTextSource extends AbstractEmailTextSource
{
    protected $text;

    public function getTextType()
    {
        return self::EMAIL_TEXT_SOURCE_TEXT;
    }

    public function toArray()
    {
        return array(
            '$type'     => "SwiftReach.Swift911.Core.Messages.Email.EMAIL_TEXT_SOURCE_TEXT, SwiftReach.Swift911.Core",
            "TextType" => $this->getTextType(),
            "Text"   => $this->getText(),
        );
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $tts_text
     * @return this
     */
    public function setText($tts_text)
    {
        $this->text = $tts_text;
        return $this;
    }

} 