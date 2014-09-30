<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/15/14
 * Time: 11:22 AM
 */

namespace SwiftReachApi\Voice;
use SwiftReachApi\Interfaces\JsonSerialize;
use SwiftReachApi\Interfaces\ArraySerialize;
use SwiftReachApi\Exceptions\SwiftReachException;

abstract class AbstractVoiceMessage
implements JsonSerialize
{
    CONST CONTENT_REGEX = '/[^0-9a-zA-Z.:?\'" ]/';

    CONST VOICE_TYPE_VOICE_MESSAGE = "voice_message";

    /**
     * Name of the message
     * @var  string
     */
    private $name;

    /**
     * A brief description of what the message is used for
     * @var  string
     */
    private $description;

    /**
     * a 10-digit caller-id that will be sent with the message.  Must be in the format 1234567890
     * @var  string
     */
    private $caller_id;

    /**
     * Voice code that uniquely identifies the voice message once created
     * @var  int
     */
    private $voice_code;


    /**
     * @param $caller_id
     * @return bool
     */
    public function validateCallerId($caller_id)
    {
        // if caller id contains non-numeric values or is not ten digits long, fail
        if( (preg_match('/[^0-9]/',$caller_id) || strlen($caller_id) != 10) && $caller_id != ""){
            return false;
        }else{
            return true;
        }
    }

    /**
     * check that all required fields are set
     * @throws \SwiftReachApi\Exceptions\SwiftReachApiException
     */
    abstract function requiredFieldsSet();

    /**
     * iterate through a list of fields and ensure that they are set since they are required.
     * @param $fields array of field names to check
     * @throws SwiftReachException
     */
    protected function checkRequiredFieldsSet($fields)
    {
        $missing_fields = array();
        foreach ($fields as $field) {
            $func = "get" . $field;
            if ($this->$func() == "") {
                $missing_fields[] = $field;
            }
        }
        if (!empty($missing_fields)) {
            throw new SwiftReachException("The following fields cannot be blank: " . implode(", ", $missing_fields));
        }
    }

    /**
     * @param string $caller_id
     */
    public function setCallerId($caller_id)
    {
        if($this->validateCallerId($caller_id) == false){
            throw new SwiftReachException("'".$caller_id."' is not a valid caller id.  Must contain only 10 digits.");
        }

        $this->caller_id = $caller_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallerId()
    {
        return $this->caller_id;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $voice_code
     */
    public function setVoiceCode($voice_code)
    {
        if(!is_numeric($voice_code)){
            throw new SwiftReachException("Voice code: '".$voice_code."' must be a numerical value.");
        }

        $this->voice_code = $voice_code;
        return $this;
    }

    /**
     * @return int
     */
    public function getVoiceCode()
    {
        return $this->voice_code;
    }


} 