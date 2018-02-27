<?php

namespace Ied\SwiftReachApi\Email\EmailTextSource;


class UserDefinedFieldEmailTextSource extends AbstractEmailTextSource
{
    protected $field_key;

    function __construct($field_key = '')
    {
        $this->field_key = $field_key;
    }


    public function getTextType()
    {
        return self::EMAIL_TEXT_SOURCE_USER_DEFINED_FIELD;
    }

    public function toArray()
    {
        return array(
            '$type'     => "SwiftReach.Swift911.Core.Messages.Email.EMAIL_TEXT_SOURCE_USER_DEFINED_FIELD, SwiftReach.Swift911.Core",
            "TextType" => $this->getTextType(),
            "FieldKey"   => $this->getFieldKey(),
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
     * @param mixed $tts_text
     * @return this
     */
    public function setFieldKey($field_key)
    {
        $this->field_key = $field_key;
        return $this;
    }

} 