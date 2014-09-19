<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/17/14
 * Time: 4:47 PM
 */

namespace SwiftReachApi\Voice;

use SwiftReachApi\Exceptions\SwiftReachException;


/**
 * Class AbstractMessageProfile
 * @package SwiftReachApi\Voice
 */
class MessageProfile extends AbstractVoiceMessage
{
    CONST VISIBILITY_TYPE_VISIBLE = "Visible";
    CONST VISIBILITY_TYPE_HIDDEN = "Hidden";
    CONST VISIBILITY_TYPE_TEMPORARY = "Temporary";

    /**
     * @var int
     */
    protected $capacity_limit = 0;
    /**
     * @var int
     */
    protected $ring_seconds = 60;
    /**
     * @var int
     */
    protected $congestion_attempts = 3;
    /**
     * @var int
     */
    protected $auto_retries = 1;
    /**
     * @var int
     */
    protected $auto_retries_interval = 3;
    /**
     * @var bool
     */
    protected $enable_waterfall = false;
    /**
     * @var bool
     */
    protected $enable_answering_machine_detection = false;

    /**
     * @var VoiceAlertProfile
     */
    protected $content_profile;

    /**
     * @var timestamp
     */
    protected $create_stamp;
    /**
     * @var timestamp
     */
    protected $change_stamp;
    /**
     * @var timestamp
     */
    protected $last_used;
    /**
     * @var string
     */
    protected $created_by_user;
    /**
     * @var string
     */
    protected $changed_by_user;
    /**
     * @var string
     */
    protected $voice_type;
    /**
     * @var string
     */
    protected $visibility;
    /**
     * @var bool
     */
    protected $delete_locked = true;


    public function populateFromArray($a)
    {
        $special_functions = array(
            "CallerID" => "setCalledId",
            "ContentProfile" => "populateContentProfileFromArray"
        );

        foreach ($a as $key => $value) {
            if (in_array($key, array_keys($special_functions))) {
                $set_method = $special_functions[$key];
            } else {
                $set_method = "set" . $key;
            }

            if (method_exists($this, $set_method)) {
                $this->$set_method($value);
            }
        }
    }

    private function populateContentProfileFromArray($a){
        $content_profile = new VoiceAlertProfile();
        $content_profile->populateFromArray($a);
        $this->setContentProfile($content_profile);
    }

    public function validateVisibility($visibility)
    {
        return in_array($visibility, $this->getVisibilityTypes());
    }

    public function getVisibilityTypes()
    {
        return array(
            self::VISIBILITY_TYPE_VISIBLE,
            self::VISIBILITY_TYPE_HIDDEN,
            self::VISIBILITY_TYPE_TEMPORARY,
        );
    }

    /**
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
        if (!$this->validateVisibility($visibility)) {
            throw new SwiftReachException("'" . $visibility . "' is not a valid message profile visibility type");
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getVoiceType()
    {
        return $this->voice_type;
    }

    /**
     * @param string $voice_type
     */
    public function setVoiceType($voice_type)
    {
        $this->voice_type = $voice_type;
        return $this;
    }

    /**
     * @return int
     */
    public function getAutoRetries()
    {
        return $this->auto_retries;
    }

    /**
     * @param int $auto_retries
     */
    public function setAutoRetries($auto_retries)
    {
        $this->auto_retries = $auto_retries;
        return $this;
    }

    /**
     * @return int
     */
    public function getAutoRetriesInterval()
    {
        return $this->auto_retries_interval;
    }

    /**
     * @param int $auto_retries_interval
     */
    public function setAutoRetriesInterval($auto_retries_interval)
    {
        $this->auto_retries_interval = $auto_retries_interval;
        return $this;
    }

    /**
     * @return int
     */
    public function getCapacityLimit()
    {
        return $this->capacity_limit;
    }

    /**
     * @param int $capacity_limit
     */
    public function setCapacityLimit($capacity_limit)
    {
        $this->capacity_limit = $capacity_limit;
        return $this;
    }

    /**
     * @return timestamp
     */
    public function getChangeStamp()
    {
        return $this->change_stamp;
    }

    /**
     * @param timestamp $change_stamp
     */
    public function setChangeStamp($change_stamp)
    {
        $this->change_stamp = $change_stamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getChangedByUser()
    {
        return $this->changed_by_user;
    }

    /**
     * @param string $changed_by_user
     */
    public function setChangedByUser($changed_by_user)
    {
        $this->changed_by_user = $changed_by_user;
        return $this;
    }

    /**
     * @return int
     */
    public function getCongestionAttempts()
    {
        return $this->congestion_attempts;
    }

    /**
     * @param int $congestion_attempts
     */
    public function setCongestionAttempts($congestion_attempts)
    {
        $this->congestion_attempts = $congestion_attempts;
        return $this;
    }

    /**
     * @return timestamp
     */
    public function getCreateStamp()
    {
        return $this->create_stamp;
    }

    /**
     * @param timestamp $create_stamp
     */
    public function setCreateStamp($create_stamp)
    {
        $this->create_stamp = $create_stamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedByUser()
    {
        return $this->created_by_user;
    }

    /**
     * @param string $created_by_user
     */
    public function setCreatedByUser($created_by_user)
    {
        $this->created_by_user = $created_by_user;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDeleteLocked()
    {
        return $this->delete_locked;
    }

    /**
     * @param boolean $delete_locked
     */
    public function setDeleteLocked($delete_locked)
    {
        $this->delete_locked = $delete_locked;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableAnsweringMachineDetection()
    {
        return $this->enable_answering_machine_detection;
    }

    /**
     * @param boolean $enable_answering_machine_detection
     */
    public function setEnableAnsweringMachineDetection($enable_answering_machine_detection)
    {
        $this->enable_answering_machine_detection = $enable_answering_machine_detection;
        return $this;
    }

    /**
     * @return VoiceAlertProfile
     */
    public function getContentProfile()
    {
        return $this->content_profile;
    }

    /**
     * @param VoiceAlertProfile $content_profile
     */
    public function setContentProfile($content_profile)
    {
        $this->content_profile = $content_profile;
        return $this;
    }


    /**
     * @return boolean
     */
    public function isEnableWaterfall()
    {
        return $this->enable_waterfall;
    }

    /**
     * @param boolean $enable_waterfall
     */
    public function setEnableWaterfall($enable_waterfall)
    {
        $this->enable_waterfall = $enable_waterfall;
        return $this;
    }

    /**
     * @return timestamp
     */
    public function getLastUsed()
    {
        return $this->last_used;
    }

    /**
     * @param timestamp $last_used
     */
    public function setLastUsed($last_used)
    {
        $this->last_used = $last_used;
        return $this;
    }

    /**
     * @return int
     */
    public function getRingSeconds()
    {
        return $this->ring_seconds;
    }

    /**
     * @param int $ring_seconds
     */
    public function setRingSeconds($ring_seconds)
    {
        $this->ring_seconds = $ring_seconds;
        return $this;
    }

}