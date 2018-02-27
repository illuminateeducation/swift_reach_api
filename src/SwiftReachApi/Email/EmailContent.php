<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 12:18 PM
 */

namespace Ied\SwiftReachApi\Email;

use Ied\SwiftReachApi\Interfaces\ArraySerialize;
use Ied\SwiftReachApi\Email\EmailTextSource\AbstractEmailTextSource;
use Ied\SwiftReachApi\Email\EmailTextSource\TextEmailTextSource;
use Ied\SwiftReachApi\Email\EmailTextSource\AutoFieldEmailTextSource;
use Ied\SwiftReachApi\Email\EmailTextSource\UserDefinedFieldEmailTextSource;
use Ied\SwiftReachApi\Exceptions\SwiftReachException;


class EmailContent
    implements ArraySerialize
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
     * @var array EmailTextSource
     */
    protected $body = array();

    public function toArray()
    {
        $bodies = array();
        foreach ($this->getBody() as $b) {
            $bodies[] = $b->toArray();
        }

        return array(
            "SpokenLanguage" => $this->getSpokenLanguage(),
            "Subject"       => $this->getSubject(),
            "Body"      => $bodies,
        );
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
                $email_text_source = $this->createEmailTextSourceByType($ets["TextType"]);
                $email_text_source->populateFromArray($ets);
                $this->addBody($email_text_source);;
            }
        }
    }
    /**
     * @param $audio_source_type
     * @throws SwiftReachException
     */
    public function createEmailTextSourceByType($email_text_source)
    {
        switch ($email_text_source) {
            case AbstractEmailTextSource::EMAIL_TEXT_SOURCE_TEXT:
            case "0":
                return new TextEmailTextSource();
            case AbstractEmailTextSource::EMAIL_TEXT_SOURCE_AUTO_FIELD:
            case "1":
                return new AutoFieldEmailTextSource();
            case AbstractEmailTextSource::EMAIL_TEXT_SOURCE_USER_DEFINED_FIELD:
            case "2":
                return new UserDefinedFieldEmailTextSource();
            default:
                throw new SwiftReachException("Couldn't create an email text source of type: '" . $email_text_source
                    . "'.");
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
     * @param string $tty_text
     */
    public function setSubject($tty_text)
    {
        $this->subject = $tty_text;

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
     * @param array $voice_items
     */
    public function setBody($email_text_sources)
    {
        $this->body = array();
        foreach ($email_text_sources as $ets) {
            $this->addBody($ets);
        }

        return $this;
    }

    public function addBody($email_text_source)
    {
        $this->body[] = $email_text_source;
        return $this;
    }
} 