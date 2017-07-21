<?php

namespace SwiftReachApi\Sms\SmsTextSource;


use SwiftReachApi\Interfaces\ArraySerialize;

abstract class AbstractSmsTextSource
    implements ArraySerialize
{
    const SMS_TEXT_SOURCE_TEXT = "sms_text_source_text";
    const SMS_TEXT_SOURCE_AUTO_FIELD = "sms_text_source_auto_field";
    const SMS_TEXT_SOURCE_USER_DEFINED_FIELD = "sms_text_source_user_defined_field";

    abstract public function getTextType();

    public function getSmsTextSourceTypes()
    {
        return array(
            self::SMS_TEXT_SOURCE_TEXT,
            self::SMS_TEXT_SOURCE_AUTO_FIELD,
            self::SMS_TEXT_SOURCE_USER_DEFINED_FIELD,
        );
    }

    public function populateFromArray($a)
    {
        $fields = get_object_vars($this);
        unset($fields["TextType"]);

        foreach ($fields as $f => $value) {
            $key = implode("", array_map("ucfirst", explode("_", $f)));
            if (isset($a[$key])) {
                $method = "set" . $key;
                $this->$method($a[$key]);
            }
        }
    }


} 
