<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/17/14
 * Time: 4:47 PM
 */

namespace SwiftReachApi\Email;

use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Interfaces\JsonSerialize;


class EmailMessage implements JsonSerialize
{
    CONST VISIBILITY_TYPE_VISIBLE = "Visible";
    CONST VISIBILITY_TYPE_HIDDEN = "Hidden";
    CONST VISIBILITY_TYPE_TEMPORARY = "Temporary";

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
    protected $email_code = '';

    /** @var string */
    protected $name = '';

    /** @var string */
    protected $description = '';

    /** @var string */
    protected $from_name = '';

    /** @var string */
    protected $from_address = '';

    /** @var string */
    protected $default_spoken_language = "English";

    /** @var array */
    protected $content = array();

    /** @var string */
    protected $visibility;

    /** @var bool */
    protected $delete_locked = true;


    public function toJson()
    {
        $a = array(
            "Name" => $this->getName(),
            "Description" => $this->getDescription(),
            "FromName" => $this->getFromName(),
            "FromAddress" => $this->getFromAddress(),
            "DefaultSpokenLanguage" => $this->getDefaultSpokenLanguage(),
            "Content" => array(),
            "Visibility" => $this->getVisibility(),
            "DeleteLocked" => $this->isDeleteLocked()
        );

        foreach($this->getContent() as $c){
            $a["Content"][] = $c->toArray();
        }

        return json_encode($a);
    }

    public function requiredFieldsSet()
    {
        $required_fields = array("Name", "Description", "FromName", "FromAddress");
        $missing_fields = array();
        foreach ($required_fields as $field) {
            $func = "get" . $field;
            if ($this->$func() == "") {
                $missing_fields[] = $field;
            }
        }
        if (!empty($missing_fields)) {
            throw new SwiftReachException("The following fields cannot be blank: " . implode(", ", $missing_fields));
        }
    }


    public function populateFromArray($a)
    {
        $special_functions = array(
            "Content" => "populateContentFromArray"
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

    private function populateContentFromArray($a)
    {
        foreach ($a as $c) {
            $content = new EmailContent();
            $content->populateFromArray($c);
            $this->addContent($content);
        }
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
        $this->content = array();
        foreach ($content as $c) {
            $this->addContent($c);
        }

        return $this;
    }

    public function addContent(EmailContent $content)
    {
        $this->content[] = $content;

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

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailCode()
    {
        return $this->email_code;
    }

    /**
     * @param string $email_code
     */
    public function setEmailCode($email_code)
    {
        $this->email_code = $email_code;

        return $this;
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

        return $this;
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

}