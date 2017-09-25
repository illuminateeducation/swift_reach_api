<?php
/** @filesource */

namespace SwiftReachApi\Sms;

use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\ArraySerialize;
use SwiftReachApi\Sms\SmsTextSource\AbstractSmsTextSource;
use SwiftReachApi\Sms\SmsTextSource\AutoFieldSmsTextSource;
use SwiftReachApi\Sms\SmsTextSource\TextSmsTextSource;
use SwiftReachApi\Sms\SmsTextSource\UserDefinedFieldSmsTextSource;

class SmsContent implements ArraySerialize
{
    /**
     * @var string
     */
    protected $spoken_language = 'English';

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var array SmsTextSource
     */
    protected $body = [];


    public function toArray()
    {
        $bodies = [];
        foreach ($this->getBody() as $b) {
            $bodies[] = $b->toArray();
        }

        return [
            "SpokenLanguage" => $this->getSpokenLanguage(),
            "Subject"        => $this->getSubject(),
            "Body"           => $bodies,
        ];
    }

    public function populateFromArray($a)
    {
        if (isset($a["SpokenLanguage"])) {
            $this->setSpokenLanguage($a["SpokenLanguage"]);
        }
        if (isset($a["Subject"])) {
            $this->setSubject($a["Subject"]);
        }

        if (isset($a["Body"])) {
            foreach ($a["Body"] as $ets) {
                $email_text_source = $this->createSmsTextSourceByType($ets["TextType"]);
                $email_text_source->populateFromArray($ets);
                $this->addBodyPart($email_text_source);
            }
        }
    }

    /**
     * @param $sms_text_source
     *
     * @return AbstractSmsTextSource
     * @throws SwiftReachException
     */
    public function createSmsTextSourceByType($sms_text_source)
    {
        switch ($sms_text_source) {
            case AbstractSmsTextSource::SMS_TEXT_SOURCE_TEXT:
            case "0":
                return new TextSmsTextSource();
            case AbstractSmsTextSource::SMS_TEXT_SOURCE_AUTO_FIELD:
            case "1":
                return new AutoFieldSmsTextSource();
            case AbstractSmsTextSource::SMS_TEXT_SOURCE_USER_DEFINED_FIELD:
            case "2":
                return new UserDefinedFieldSmsTextSource();
            default:
                throw new SwiftReachException("Couldn't create sms text source of type: '" . $sms_text_source . "'.");
        }
    }

    /**
     * @return string
     */
    public function getSpokenLanguage()
    {
        return $this->spoken_language;
    }

    /**
     * @param string $spoken_language
     *
     * @return SmsContent
     */
    public function setSpokenLanguage($spoken_language)
    {
        $this->spoken_language = $spoken_language;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return SmsContent
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array $body
     *
     * @return SmsContent
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function addBodyPart(AbstractSmsTextSource $txtSource)
    {
        $this->body[] = $txtSource;
    }
}
