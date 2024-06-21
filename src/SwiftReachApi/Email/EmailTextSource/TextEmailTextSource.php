<?php

namespace Ied\SwiftReachApi\Email\EmailTextSource;


class TextEmailTextSource extends AbstractEmailTextSource
{
    protected $text;

    function __construct($text = '')
    {
        $this->text = $text;
    }


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
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

} 