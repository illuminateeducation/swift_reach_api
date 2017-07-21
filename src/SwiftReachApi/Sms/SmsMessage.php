<?php
/** @filesource */

namespace SwiftReachApi\Sms;


use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\JsonSerialize;

class SmsMessage implements JsonSerialize
{
    CONST VISIBILITY_TYPE_VISIBLE   = "Visible";
    CONST VISIBILITY_TYPE_HIDDEN    = "Hidden";
    CONST VISIBILITY_TYPE_TEMPORARY = "Temporary";


    const REPLY_TYPE_NONE     = 'None';
    const REPLY_TYPE_YESNO    = 'YesNo';
    const REPLY_TYPE_NUMERIC  = 'Numeric';
    const REPLY_TYPE_RANGE    = 'Range';
    const REPLY_TYPE_CUSTOM   = 'Custom';
    const REPLY_TYPE_REGEX    = 'Regex';
    const REPLY_TYPE_FREEFORM = 'Freeform';

    /** @var timestamp */
    protected $create_stamp;

    /** @var timestamp */
    protected $change_stamp;

    /** @var timestamp */
    protected $last_used;

    /** @var string */
    protected $created_by_user;

    /** @var string */
    protected $changed_by_user;

    /** @var string */
    protected $sms_code = '';

    /** @var string */
    protected $name = '';

    /** @var string */
    protected $description = '';

    /** @var string */
    protected $from_name = '';

    /** @var string email address of sender */
    protected $from_address = '';

    /** @var string */
    protected $default_spoken_language = "English";

    /** @var array */
    protected $content = [];

    /** @var  string */
    protected $reply_type;

    /** @var  string */
    protected $reply_criteria;

    /** @var  int Number of minutes after sending the message for which to accept inbound replies */
    protected $reply_window;

    /** @var  string */
    protected $reply_confirmation;

    /** @var string */
    protected $visibility;

    /** @var bool */
    protected $delete_locked = true;

    public function toJson()
    {
        $a = [
            "SMSCode"               => $this->getSmsCode(),
            "Name"                  => $this->getName(),
            "Description"           => $this->getDescription(),
            "FromName"              => $this->getFromName(),
            "FromAddress"           => $this->getFromAddress(),
            "DefaultSpokenLanguage" => $this->getDefaultSpokenLanguage(),
            "Body"                  => [],
            "Visibility"            => $this->getVisibility(),
            "DeleteLocked"          => $this->isDeleteLocked(),
            "ReplyType"             => $this->getReplyType(),
            "ReplyCriteria"         => $this->getReplyCriteria(),
            "ReplyWindow"           => $this->getReplyWindow(),
            "ReplyConfirmation"     => $this->getReplyConfirmation(),
        ];

        foreach ($this->getContent() as $c) {
            $a["Body"][] = $c->toArray();
        }

        return json_encode($a);
    }

    public function requiredFieldsSet()
    {
        $required_fields = ["Name", "Description", "FromName", "FromAddress", "ReplyType", "Body"];
        $missing_fields  = [];
        foreach ($required_fields as $field) {
            $func = "get" . $field;

            $value = $this->$func();
            if ($value == "") {
                $missing_fields[] = $field;
            }
        }
        if (!empty($missing_fields)) {
            throw new SwiftReachException("The following fields cannot be blank: " . implode(", ", $missing_fields));
        }
    }


    public function populateFromArray($a)
    {
        $special_functions = [
            "Body" => "populateContentFromArray",
        ];

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

    private function populateContentFromArray($a)
    {
        foreach ($a as $c) {
            $content = new SmsContent();
            $content->populateFromArray($c);
            $this->addContent($content);
        }
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
    }

    /**
     * @return string
     */
    public function getSmsCode()
    {
        return $this->sms_code;
    }

    /**
     * @param string $sms_code
     */
    public function setSmsCode($sms_code)
    {
        $this->sms_code = $sms_code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->from_name;
    }

    /**
     * @param string $from_name
     */
    public function setFromName($from_name)
    {
        $this->from_name = $from_name;
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return $this->from_address;
    }

    /**
     * @param string $from_address
     */
    public function setFromAddress($from_address)
    {
        $this->from_address = $from_address;
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
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function addContent(SmsContent $content)
    {
        $this->content[] = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getReplyType()
    {
        return $this->reply_type;
    }

    /**
     * @param string $reply_type
     */
    public function setReplyType($reply_type)
    {
        $this->reply_type = $reply_type;
    }

    /**
     * @return string
     */
    public function getReplyCriteria()
    {
        return $this->reply_criteria;
    }

    /**
     * @param string $reply_criteria
     */
    public function setReplyCriteria($reply_criteria)
    {
        $this->reply_criteria = $reply_criteria;
    }

    /**
     * @return int
     */
    public function getReplyWindow()
    {
        return $this->reply_window;
    }

    /**
     * @param int $reply_window
     */
    public function setReplyWindow($reply_window)
    {
        $this->reply_window = $reply_window;
    }

    /**
     * @return string
     */
    public function getReplyConfirmation()
    {
        return $this->reply_confirmation;
    }

    /**
     * @param string $reply_confirmation
     */
    public function setReplyConfirmation($reply_confirmation)
    {
        $this->reply_confirmation = $reply_confirmation;
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
    }

    /**
     * @return bool
     */
    public function isDeleteLocked()
    {
        return $this->delete_locked;
    }

    /**
     * @param bool $delete_locked
     */
    public function setDeleteLocked($delete_locked)
    {
        $this->delete_locked = $delete_locked;
    }

}
