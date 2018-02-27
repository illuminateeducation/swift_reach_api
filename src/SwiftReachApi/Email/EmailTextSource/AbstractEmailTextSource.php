<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 12:32 PM
 */

namespace Ied\SwiftReachApi\Email\EmailTextSource;


use Ied\SwiftReachApi\Interfaces\ArraySerialize;

abstract class AbstractEmailTextSource
    implements ArraySerialize
{
    const EMAIL_TEXT_SOURCE_TEXT = "email_text_source_text";
    const EMAIL_TEXT_SOURCE_AUTO_FIELD = "email_text_source_auto_field";
    const EMAIL_TEXT_SOURCE_USER_DEFINED_FIELD = "email_text_source_user_defined_field";

    abstract public function getTextType();

    public function getEmailTextSourceTypes()
    {
        return array(
            self::EMAIL_TEXT_SOURCE_TEXT,
            self::EMAIL_TEXT_SOURCE_AUTO_FIELD,
            self::EMAIL_TEXT_SOURCE_USER_DEFINED_FIELD,
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