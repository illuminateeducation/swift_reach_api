<?php

namespace Ied\SwiftReachApi\Contact;

use Ied\SwiftReachApi\Interfaces\ArraySerialize;
use Ied\SwiftReachApi\Interfaces\JsonSerialize;
use Ied\SwiftReachApi\Contact\Contact;

class ContactArray
    implements JsonSerialize, ArraySerialize
{

    /**
     * @var array
     */
    private $contacts = array();

    /**
     * @return string Json Object serialized
     */
    public function toArray()
    {
        $a = array();
        foreach ($this->getContacts() as $c) {
            /** @var $c Contact */
            $a[] = $c->toArray();
        }

        return $a;
    }

    /**
     * @return string Json Object serialized
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * @param array $contacts
     * @return $this
     */
    public function setContacts($contacts)
    {
        $this->contacts = array();
        foreach ($contacts as $vc) {
            $this->addContact($vc);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getContacts()
    {
        return $this->contacts;
    }


    /**
     * @param Contact $c
     * @return $this
     */
    public function addContact(Contact $c)
    {
        $this->contacts[] = $c;
        return $this;
    }

} 