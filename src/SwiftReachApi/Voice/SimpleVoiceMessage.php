<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:07 AM
 */

namespace SwiftReachApi\Voice;

use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\JsonSerialize;

class SimpleVoiceMessage
    extends AbstractVoiceMessage
{
    /**
     * Auto generates the message content using text-to-speech at the time the message is created.
     * @var  boolean
     */
    private $use_tts = true;

    /**
     * Content of the message
     * @var  string
     */
    private $content;

    public function toJson()
    {
        return json_encode(
            array(
                "Name"        => $this->getName(),
                "Description" => $this->getDescription(),
                "CallerID"    => $this->getCallerId(),
                "UseTTS"      => $this->getUseTts(),
                "Content"     => $this->getContent(),
            )
        );
    }



    /**
     * @param $content
     * @return bool
     */
    public function validateContent($content)
    {
        // if content contains special characters, fail
        // if content is less than 10 characters
        if(preg_match(self::CONTENT_REGEX, $content) || strlen($content) < 10){
            return false;
        }else{
            return true;
        }
    }


    /**
     * @param string $content
     */
    public function setContent($content)
    {
        if(! $this->validateContent($content)){
            throw new SwiftReachException("The message content contained characters that match ".self::CONTENT_REGEX." and are invalid or is shorter than 10 characters");
        }
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param boolean $use_tts
     */
    public function setUseTts($use_tts)
    {
        $this->use_tts = $use_tts;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getUseTts()
    {
        return $this->use_tts;
    }


}