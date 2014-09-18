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
     * @var bool
     */
    protected $required_response = false;
    /**
     * @var string comma separated list of valid options
     */
    protected $valid_responses;
    /**
     * @var
     */
    protected $content_profile;
    /**
     * @var string
     */
    protected $default_spoken_language;

    /**
     * @return mixed
     */
    public function getContentProfile()
    {
        return $this->content_profile;
    }

    /**
     * @param mixed $content_profile
     */
    public function setContentProfile($content_profile)
    {
        $this->content_profile = $content_profile;
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
     */
    public function setValidResponses($valid_responses)
    {
        $this->valid_responses = $valid_responses;
        return $this;
    }


} 