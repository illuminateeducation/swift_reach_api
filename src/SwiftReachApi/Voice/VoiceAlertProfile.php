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
        return array(
            "SpokenLanguage" => '',
            "TTY_Text" => '',
            "VoiceItem" => '',
        );
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }


    public function addVoiceAlertContent($voice_alert_content)
    {
        $this->voice_items[] = $voice_alert_content;
    }
} 