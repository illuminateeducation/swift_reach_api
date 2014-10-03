<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 12:18 PM
 */

namespace SwiftReachApi\Voice;


use SwiftReachApi\Interfaces\ArraySerialize;

class VoiceAlertProfile
    implements ArraySerialize
{

    /**
     * @var string
     */
    protected $spoken_language = 'English';

    /**
     * @var string
     */
    protected $tty_text;

    /**
     * @var array VoiceAlertContent
     */
    protected $voice_items = array();

    public function toArray()
    {
        $voice_items = array();
        foreach ($this->getVoiceItems() as $vi) {
            $voice_items[] = $vi->toArray();
        }

        return array(
            "SpokenLanguage" => $this->getSpokenLanguage(),
            "TTY_Text"       => $this->getTtyText(),
            "VoiceItem"      => $voice_items,
        );
    }

    public function populateFromArray($a)
    {
        if (isset($a["SpokenLanguage"])) {
            $this->setSpokenLanguage($a["SpokenLanguage"]);
        }
        if (isset($a["TTY_Text"])) {
            $this->setTtyText($a["TTY_Text"]);
        }

        if (isset($a["VoiceItem"])) {
            foreach ($a["VoiceItem"] as $vi) {
                $voice_alert_profile = new VoiceAlertContent();
                $voice_alert_profile->populateFromArray($vi);
                $this->addVoiceAlertContent($voice_alert_profile);
            }
        }

    }


    public function addVoiceAlertContent(VoiceAlertContent $voice_alert_content)
    {
        $this->voice_items[] = $voice_alert_content;
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
    public function getTtyText()
    {
        return $this->tty_text;
    }

    /**
     * @param string $tty_text
     */
    public function setTtyText($tty_text)
    {
        $this->tty_text = $tty_text;

        return $this;
    }

    /**
     * @return array
     */
    public function getVoiceItems()
    {
        return $this->voice_items;
    }

    /**
     * @param array $voice_items
     */
    public function setVoiceItems($voice_items)
    {
        $this->voice_items = $voice_items;

        return $this;
    }
} 