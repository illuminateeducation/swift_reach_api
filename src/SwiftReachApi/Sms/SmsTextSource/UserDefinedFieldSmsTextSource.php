<?php

namespace SwiftReachApi\Sms\SmsTextSource;


class UserDefinedFieldSmsTextSource extends AbstractSmsTextSource
{
    protected $field_key;

    function __construct($field_key = '')
    {
        $this->field_key = $field_key;
    }


    public function getTextType()
    {
        return self::SMS_TEXT_SOURCE_USER_DEFINED_FIELD;
    }

    public function toArray()
    {
        return [
            '$type'    => "SwiftReach.Swift911.Core.Messages.SMS.SMS_TEXT_SOURCE_USER_DEFINED_FIELD, SwiftReach.Swift911.Core",
            "TextType" => $this->getTextType(),
            "FieldKey" => $this->getFieldKey(),
        ];
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
     *
     * @return UserDefinedFieldSmsTextSource
     */
    public function setFieldKey($field_key)
    {
        $this->field_key = $field_key;

        return $this;
    }

} 
