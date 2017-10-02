<?php
/** @filesource */

namespace SwiftReachApi\Report;


use SwiftReachApi\Exceptions\SwiftReachException;

class AlertCampaignProgress
{
    const STATE_QUEUED     = 'job_queued';
    const STATE_INPROGRESS = 'job_inprogress';
    const STATE_PAUSED     = 'job_paused';
    const STATE_COMPLETED  = 'job_completed';

    /** @var  int */
    private $voice_size = 0;

    /** @var  int */
    private $calls_remaining = 0;

    /** @var int */
    private $calls_answered = 0;

    /** @var int */
    private $calls_unanswered = 0;

    /** @var int */
    private $calls_busy = 0;

    /** @var int */
    private $calls_operator_intercepted = 0;

    /** @var int */
    private $calls_congested = 0;

    /** @var int */
    private $total_calls = 0;

    /** @var int */
    private $emails_remaining = 0;

    /** @var int */
    private $emails_sent = 0;

    /** @var int */
    private $email_size = 0;

    /** @var int */
    private $smss_remaining = 0;

    /** @var int */
    private $smss_sent = 0;

    /** @var int */
    private $sms_size = 0;

    /** @var int */
    private $pages_remaining = 0;

    /** @var int */
    private $pages_sent = 0;

    /** @var int */
    private $page_size = 0;

    /** @var int */
    private $fax_size = 0;

    /** @var int */
    private $pushs_remaining = 0;

    /** @var int */
    private $pushs_sent = 0;

    /** @var int */
    private $push_size = 0;

    /** @var int */
    private $job_state = 0;


    public function populateFromArray($a)
    {
        foreach ($a as $key => $value) {
            $method = 'set'.$key;

            if(method_exists($this, $method)) {

                if($method == 'setJobState'){
                    $this->setJobState($this->intToJobState($value));
                } else {
                    $this->$method($value);
                }
            }
        }
    }

    public function intToJobState($value)
    {
        switch($value)
        {
            case 0:
                return self::STATE_QUEUED;
            case 1:
                return self::STATE_INPROGRESS;
            case 2:
                return self::STATE_PAUSED;
            case 3:
                return self::STATE_COMPLETED;
        }

        throw new SwiftReachException($value.' is not a valid job_state value');
    }

    /**
     * @return int
     */
    public function getVoiceSize()
    {
        return $this->voice_size;
    }

    /**
     * @param int $voice_size
     *
     * @return AlertCampaignProgress
     */
    public function setVoiceSize($voice_size)
    {
        $this->voice_size = $voice_size;

        return $this;
    }

    /**
     * @return int
     */
    public function getCallsRemaining()
    {
        return $this->calls_remaining;
    }

    /**
     * @param int $calls_remaining
     *
     * @return AlertCampaignProgress
     */
    public function setCallsRemaining($calls_remaining)
    {
        $this->calls_remaining = $calls_remaining;

        return $this;
    }

    /**
     * @return int
     */
    public function getCallsAnswered()
    {
        return $this->calls_answered;
    }

    /**
     * @param int $calls_answered
     *
     * @return AlertCampaignProgress
     */
    public function setCallsAnswered($calls_answered)
    {
        $this->calls_answered = $calls_answered;

        return $this;
    }

    /**
     * @return int
     */
    public function getCallsUnanswered()
    {
        return $this->calls_unanswered;
    }

    /**
     * @param int $calls_unanswered
     *
     * @return AlertCampaignProgress
     */
    public function setCallsUnanswered($calls_unanswered)
    {
        $this->calls_unanswered = $calls_unanswered;

        return $this;
    }

    /**
     * @return int
     */
    public function getCallsBusy()
    {
        return $this->calls_busy;
    }

    /**
     * @param int $calls_busy
     *
     * @return AlertCampaignProgress
     */
    public function setCallsBusy($calls_busy)
    {
        $this->calls_busy = $calls_busy;

        return $this;
    }

    /**
     * @return int
     */
    public function getCallsOperatorIntercepted()
    {
        return $this->calls_operator_intercepted;
    }

    /**
     * @param int $calls_operator_intercepted
     *
     * @return AlertCampaignProgress
     */
    public function setCallsOperatorIntercepted($calls_operator_intercepted)
    {
        $this->calls_operator_intercepted = $calls_operator_intercepted;

        return $this;
    }

    /**
     * @return int
     */
    public function getCallsCongested()
    {
        return $this->calls_congested;
    }

    /**
     * @param int $calls_congested
     *
     * @return AlertCampaignProgress
     */
    public function setCallsCongested($calls_congested)
    {
        $this->calls_congested = $calls_congested;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCalls()
    {
        return $this->total_calls;
    }

    /**
     * @param int $total_calls
     *
     * @return AlertCampaignProgress
     */
    public function setTotalCalls($total_calls)
    {
        $this->total_calls = $total_calls;

        return $this;
    }

    /**
     * @return int
     */
    public function getEmailsRemaining()
    {
        return $this->emails_remaining;
    }

    /**
     * @param int $emails_remaining
     *
     * @return AlertCampaignProgress
     */
    public function setEmailsRemaining($emails_remaining)
    {
        $this->emails_remaining = $emails_remaining;

        return $this;
    }

    /**
     * @return int
     */
    public function getEmailsSent()
    {
        return $this->emails_sent;
    }

    /**
     * @param int $emails_sent
     *
     * @return AlertCampaignProgress
     */
    public function setEmailsSent($emails_sent)
    {
        $this->emails_sent = $emails_sent;

        return $this;
    }

    /**
     * @return int
     */
    public function getEmailSize()
    {
        return $this->email_size;
    }

    /**
     * @param int $email_size
     *
     * @return AlertCampaignProgress
     */
    public function setEmailSize($email_size)
    {
        $this->email_size = $email_size;

        return $this;
    }

    /**
     * @return int
     */
    public function getSMSsRemaining()
    {
        return $this->smss_remaining;
    }

    /**
     * @param int $smss_remaining
     *
     * @return $this
     */
    public function setSMSsRemaining($smss_remaining)
    {
        $this->smss_remaining = $smss_remaining;

        return $this;
    }

    /**
     * @return int
     */
    public function getSMSsSent()
    {
        return $this->smss_sent;
    }

    /**
     * @param int $smss_sent
     *
     * @return $this
     */
    public function setSMSsSent($smss_sent)
    {
        $this->smss_sent = $smss_sent;

        return $this;
    }

    /**
     * @return int
     */
    public function getSmsSize()
    {
        return $this->sms_size;
    }

    /**
     * @param int $sms_size
     *
     * @return AlertCampaignProgress
     */
    public function setSmsSize($sms_size)
    {
        $this->sms_size = $sms_size;

        return $this;
    }

    /**
     * @return int
     */
    public function getPagesRemaining()
    {
        return $this->pages_remaining;
    }

    /**
     * @param int $pages_remaining
     *
     * @return AlertCampaignProgress
     */
    public function setPagesRemaining($pages_remaining)
    {
        $this->pages_remaining = $pages_remaining;

        return $this;
    }

    /**
     * @return int
     */
    public function getPagesSent()
    {
        return $this->pages_sent;
    }

    /**
     * @param int $pages_sent
     *
     * @return AlertCampaignProgress
     */
    public function setPagesSent($pages_sent)
    {
        $this->pages_sent = $pages_sent;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->page_size;
    }

    /**
     * @param int $page_size
     *
     * @return AlertCampaignProgress
     */
    public function setPageSize($page_size)
    {
        $this->page_size = $page_size;

        return $this;
    }

    /**
     * @return int
     */
    public function getFaxSize()
    {
        return $this->fax_size;
    }

    /**
     * @param int $fax_size
     *
     * @return AlertCampaignProgress
     */
    public function setFaxSize($fax_size)
    {
        $this->fax_size = $fax_size;

        return $this;
    }

    /**
     * @return int
     */
    public function getPushsRemaining()
    {
        return $this->pushs_remaining;
    }

    /**
     * @param int $pushs_remaining
     *
     * @return AlertCampaignProgress
     */
    public function setPushsRemaining($pushs_remaining)
    {
        $this->pushs_remaining = $pushs_remaining;

        return $this;
    }

    /**
     * @return int
     */
    public function getPushsSent()
    {
        return $this->pushs_sent;
    }

    /**
     * @param int $pushs_sent
     */
    public function setPushsSent($pushs_sent)
    {
        $this->pushs_sent = $pushs_sent;
    }


    /**
     * @return int
     */
    public function getPushSize()
    {
        return $this->push_size;
    }

    /**
     * @param int $push_size
     *
     * @return AlertCampaignProgress
     */
    public function setPushSize($push_size)
    {
        $this->push_size = $push_size;

        return $this;
    }

    /**
     * @return int
     */
    public function getJobState()
    {
        return $this->job_state;
    }

    /**
     * @param int $job_state
     *
     * @return AlertCampaignProgress
     */
    public function setJobState($job_state)
    {
        $this->job_state = $job_state;

        return $this;
    }

}
