<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/17/14
 * Time: 5:03 PM
 */

namespace SwiftReachApi\Voice;


class VoiceMessage extends MessageProfile
{
    /**
     * @var int
     */
    protected $auto_replays = 1;

    /**
     * @var bool
     */
    protected $required_response = false;
    /**
     * @var string comma separated list of valid options
     */
    protected $valid_responses = '';
    /**
     * @var
     */
    protected $content_profiles;
    /**
     * @var string
     */
    protected $default_spoken_language = "English";

    /**
     * @var bool
     */
    protected $enable_answering_machine_message = false;

    /**
     * @var string
     */
    protected $voice_type = self::VOICE_TYPE_VOICE_MESSAGE;

    /**
     * overload the base get voice type.
     * @return string
     */
    public function getVoiceType()
    {
        return self::VOICE_TYPE_VOICE_MESSAGE;
    }

    public function getFieldsToIgnoreOnToJson()
    {
        return array(
            "visibility",
            "delete_locked",
        );
    }

    /**
     * fields had to be in a very particular order for it to work
     *
     *
     **/
    public function toJson()
    {
        $a = array(
                '$type'                           => "SwiftReach.Swift911.Core.Messages.Voice.Voice_Message, SwiftReach.Swift911.Core",
                "Name"                            => "The Voice Message",
                "Description"                     => "this is a test",
                "DefaultSpokenLanguage"           => "English",
                "AutoReplays"                     => 1,
                "RequireResponse"                 => false,
                "ValidResponses"                  => null,
                "EnableAnsweringMachineDetection" => false,
                "EnableAnsweringMachineMessage"   => false,
                "CallerID"                        => "2012361344",
                "CapacityLimit"                   => 0,
                "RingSeconds"                     => 60,
                "CongestionAttempts"              => 3,
                "AutoRetries"                     => 0,
                "AutoRetriesInterval"             => 3,
                "EnableWaterfall"                 => false,
                "VoiceType"                       => "voice_message",
            );

        $a["ContentProfile"] = array();
        foreach($this->getContentProfiles() as $cp){
            $a["ContentProfile"][] = $cp->toArray();
        }

        return json_encode($a);

    }


    /**
     * @return mixed
     */
    public function getContentProfiles()
    {
        return $this->content_profiles;
    }

    /**
     * @param mixed $content_profile
     * @return VoiceMessage this
     */
    public function setContentProfiles($content_profile)
    {
        $this->content_profiles = $content_profile;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultSpokenLanguage()
    {
        return $this->default_spoken_language;
    }

    /**
     * @param string $default_spoken_language
     * @return VoiceMessage this
     */
    public function setDefaultSpokenLanguage($default_spoken_language)
    {
        $this->default_spoken_language = $default_spoken_language;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequiredResponse()
    {
        return $this->required_response;
    }

    /**
     * @param boolean $required_response
     * @return VoiceMessage this
     */
    public function setRequiredResponse($required_response)
    {
        $this->required_response = $required_response;
        return $this;
    }

    /**
     * @return string
     */
    public function getValidResponses()
    {
        return $this->valid_responses;
    }

    /**
     * @param string $valid_responses
     * @return VoiceMessage this
     */
    public function setValidResponses($valid_responses)
    {
        $this->valid_responses = $valid_responses;
        return $this;
    }

    /**
     * @return int
     */
    public function getAutoReplays()
    {
        return $this->auto_replays;
    }

    /**
     * @param int $auto_replays
     * @return VoiceMessage this
     */
    public function setAutoReplays($auto_replays)
    {
        $this->auto_replays = $auto_replays;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableAnsweringMachineMessage()
    {
        return $this->enable_answering_machine_message;
    }

    /**
     * @param boolean $enable_answering_machine_message
     * @return VoiceMessage this
     */
    public function setEnableAnsweringMachineMessage($enable_answering_machine_message)
    {
        $this->enable_answering_machine_message = $enable_answering_machine_message;
        return $this;
    }




} 