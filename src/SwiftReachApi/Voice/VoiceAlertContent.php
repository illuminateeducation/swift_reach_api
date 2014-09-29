<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 12:25 PM
 */

namespace SwiftReachApi\Voice;


use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\ArraySerialize;
use SwiftReachApi\Voice\AudioSource\AbstractAudioSource;
use SwiftReachApi\Voice\AudioSource\TtsFieldAudioSource;
use SwiftReachApi\Voice\AudioSource\TtsTextAudioSource;
use SwiftReachApi\Voice\AudioSource\TtsUserDefinedFieldAudioSource;
use SwiftReachApi\Voice\AudioSource\VoiceAudioSource;

class VoiceAlertContent
implements ArraySerialize
{
    CONST ALERT_ANSWERING_MACHINE           = "alert_answering_machine";
    CONST ALERT_HUMAN                       = "alert_human";
    CONST ALERT_RESPONSE_CONFIRM            = "alert_response_confirm";
    CONST ALERT_ASK_FOR_PIN                 = "alert_ask_for_pin";
    CONST ALERT_INVALID_PIN                 = "alert_invalid_pin";
    CONST ALERT_HOME_ALONE_TRIGGER          = "alert_home_alone_triggered";
    CONST ALERT_HOME_ALONE_OK_RESPONSE      = "alert_home_alone_ok_response";
    CONST ALERT_HOME_ALONE_PANIC_RESPONSE   = "alert_home_alone_panic_response";
    CONST ALERT_FEEDBACK_RECORDING_BEGIN    = "alert_feedback_recording_begin";



    /** @var  string */
    protected $voice_item_type;

    /** @var array AbstractAudioSource */
    protected $audio_sources = array();


    public function populateFromArray($a)
    {
        if(isset($a["VoiceItemType"])){
            $this->setVoiceItemType($a["VoiceItemType"]);
        }

        if(isset($a["AudioSource"])){
            foreach($a["AudioSource"] as $as){
                $audio_source = $this->createAudioSourceByType($as["AudioType"]);
                $audio_source->populateFromArray($as);
                $this->addAudioSource($audio_source);;
            }
        }
    }

    public function toArray()
    {
        $audio_sources = array();
        foreach($this->getAudioSources() as $as){
            $audio_sources[] = $as->toArray();
        }

        return array(
            "VoiceItemType" => $this->getVoiceItemType(),
            "AudioSource"   => $audio_sources
        );
    }


    /**
     * @param $audio_source_type
     * @throws SwiftReachException
     */
    public function createAudioSourceByType($audio_source_type)
    {
        switch($audio_source_type)
        {
            case AbstractAudioSource::AUDIO_SOURCE_VOICE:
            case "0":
                return new VoiceAudioSource();
            case AbstractAudioSource::AUDIO_SOURCE_TTS_FIELD:
            case "1":
                return new TtsFieldAudioSource();
            case AbstractAudioSource::AUDIO_SOURCE_TTS_TEXT:
            case "2":
                return new TtsTextAudioSource();
            case AbstractAudioSource::AUDIO_SOURCE_TTS_USER_DEFINED_FIELD:
            case "3":
                return new TtsUserDefinedFieldAudioSource();
            default:
                throw new SwiftReachException("Couldn't create an audio source of type: '".$audio_source_type."'.");
        }
    }

    public function validateVoiceItemType($voice_item_type)
    {
        $item_types = $this->getVoiceItemTypes();
        // sometimes the enumerated value is given
        // sometimes its the text
        if (is_numeric($voice_item_type)) {
            if (isset($item_types[$voice_item_type])) {
                return true;
            }
        } else {
            if (in_array($voice_item_type, $item_types)) {
                return true;
            }
        }
        return false;
    }

    public function getVoiceItemTypes()
    {
        return array(
            self::ALERT_ANSWERING_MACHINE,
            self::ALERT_HUMAN,
            self::ALERT_RESPONSE_CONFIRM,
            self::ALERT_ASK_FOR_PIN,
            self::ALERT_INVALID_PIN,
            self::ALERT_HOME_ALONE_TRIGGER,
            self::ALERT_HOME_ALONE_OK_RESPONSE,
            self::ALERT_HOME_ALONE_PANIC_RESPONSE,
            self::ALERT_FEEDBACK_RECORDING_BEGIN,
        );
    }

    public function addAudioSource(AbstractAudioSource $audio_source){
        $this->audio_sources[] = $audio_source;
    }


    /**
     * @return array
     */
    public function getAudioSources()
    {
        return $this->audio_sources;
    }


    /**
     * @param $audio_sources
     * @return $this
     */
    public function setAudioSources($audio_sources)
    {
        $this->audio_sources = $audio_sources;
        return $this;
    }

    /**
     * @return string
     */
    public function getVoiceItemType()
    {
        return $this->voice_item_type;
    }

    /**
     * @param string $voice_item_type
     * @return $this
     */
    public function setVoiceItemType($voice_item_type)
    {
        $this->voice_item_type = $voice_item_type;
        if(!$this->validateVoiceItemType($this->voice_item_type)){
            throw new SwiftReachException("'".$this->voice_item_type."' isn't a valid voice alert content type.");
        }
        return $this;
    }

} 