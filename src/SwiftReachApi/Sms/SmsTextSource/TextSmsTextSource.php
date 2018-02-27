<?php

namespace SwiftReachApi\Sms\SmsTextSource;


class TextSmsTextSource extends AbstractSmsTextSource
{
    protected $text;

    function __construct($text = '')
    {
        $this->text = $text;
    }


    public function getTextType()
    {
        return self::SMS_TEXT_SOURCE_TEXT;
    }

    public function toArray()
    {
        return [
            '$type'    => "SwiftReach.Swift911.Core.Messages.SMS.SMS_TEXT_SOURCE_TEXT, SwiftReach.Swift911.Core",
            "TextType" => $this->getTextType(),
            "Text"     => $this->getText(),
        ];
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $tts_text
     *
     * @return TextSmsTextSource
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

} 
