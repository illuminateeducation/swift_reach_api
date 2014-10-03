<?php

namespace SwiftReachApi\Email\EmailTextSource;


use SwiftReachApi\Exceptions\SwiftReachException;

class AutoFieldEmailTextSource extends AbstractEmailTextSource
{
    const AUTO_FIELD_ENTITY_NAME = "auto_field_entityname";
    const AUTO_FIELD_PHONE = "auto_field_phone";
    const AUTO_FIELD_DATE = "auto_field_date";
    const AUTO_FIELD_TIME = "auto_field_time";
    const AUTO_FIELD_DATETIME = "auto_field_datetime";
    const AUTO_FIELD_TAG = "auto_field_tag";

    protected $field_type;

    function __construct($field_type = '')
    {
        if ($field_type) {
            $this->setFieldType($field_type);
        }
    }


    public function getTextType()
    {
        return self::EMAIL_TEXT_SOURCE_AUTO_FIELD;
    }

    public function toArray()
    {
        return array(
            '$type'     => "SwiftReach.Swift911.Core.Messages.Email.EMAIL_TEXT_SOURCE_AUTO_FIELD, SwiftReach.Swift911.Core",
            "TextType" => $this->getTextType(),
            "FieldType"   => $this->getFieldtype(),
        );
    }

    /**
     * @return mixed
     */
    public function getFieldType()
    {
        return $this->field_type;
    }

    /**
     * @param mixed $tts_text
     * @return this
     */
    public function setFieldType($field_type)
    {
        $this->field_type = $field_type;
        if(!$this->validateFieldType($field_type)){
            throw new SwiftReachException("'$field_type' is not a valid auto field type'");
        }
        return $this;
    }

    public function validateFieldType($field_type)
    {
        return in_array($field_type, $this->getFieldTypes());
    }

    public function getFieldTypes()
    {
        return array(
            self::AUTO_FIELD_ENTITY_NAME,
            self::AUTO_FIELD_PHONE,
            self::AUTO_FIELD_DATE,
            self::AUTO_FIELD_TIME,
            self::AUTO_FIELD_DATETIME,
            self::AUTO_FIELD_TAG,
        );
    }

} 