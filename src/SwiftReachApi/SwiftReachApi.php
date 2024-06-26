<?php
namespace SwiftReachApi;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use SwiftReachApi\Contact\ContactArray;
use SwiftReachApi\Email\EmailContent;
use SwiftReachApi\Email\EmailMessage;
use SwiftReachApi\Email\SimpleEmailMessage;
use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Report\AlertCampaignProgress;
use SwiftReachApi\Sms\SmsContent;
use SwiftReachApi\Sms\SmsMessage;
use SwiftReachApi\Voice\AbstractVoiceMessage;
use SwiftReachApi\Voice\MessageProfile;
use SwiftReachApi\Voice\SimpleVoiceMessage;
use SwiftReachApi\Voice\VoiceAlertContent;
use SwiftReachApi\Voice\VoiceMessage;

class SwiftReachApi
{
    /**
     * @var string
     */
    private $api_key;

    /**
     * @var string
     * base url of the swiftreach api witout trailing slash
     * @example http://api.v4.swiftreach.com
     */
    private $base_url;

    /**
     * @var array
     * default request options
     */
    private $request_options;

    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzle_client;

    /**
     * SwiftReachApi constructor.
     * @param $api_key
     * @param string $base_url
     * @param array $request_options
     */
    public function __construct($api_key, $base_url = "http://api.v4.swiftreach.com", array $request_options = [])
    {
        $default_options = $this->getDefaultRequestOptions();

        $request_options = array_merge(
            $default_options,
            $request_options
        );

        $this->setApiKey($api_key);
        $this->setBaseUrl($base_url);
        $this->setRequestOptions($request_options);

        $this->guzzle_client = new Client($default_options);
    }

    /**
     * @return array
     */
    protected function getDefaultRequestOptions()
    {
        $default_options = [
            "timeout"         => 5,
            "connect_timeout" => 5,
        ];
        return $default_options;
    }

    //-----------------------------------------------------------------------------------------------------------------
    //  Start Email Functions
    //-----------------------------------------------------------------------------------------------------------------

    /**
     * Create a simple email function
     *
     * @param SimpleEmailMessage $simple_email obj
     *
     * @return mixed
     * @throws SwiftReachException
     * @link
     */
    public function createSimpleEmailMessage(SimpleEmailMessage $simple_email)
    {
        $url = $this->getBaseUrl() . "/api/Messages/Email/Create/Simple";

        //test for empty fields
        $simple_email->requiredFieldsSet();

        $response = $this->post($url, $simple_email->toJson());
        return $response->getBody();
    }

    public function createEmailMessage(EmailMessage $email_message)
    {
        $path = "/api/Messages/Email/Create";

        //test for empty fields
        $email_message->requiredFieldsSet();

        return $this->post($this->getBaseUrl() . $path, $email_message->toJson())->json();
    }

    public function updateEmailMessage($email_code, EmailMessage $email_message)
    {
        $path = "/api/Messages/Email/Update/$email_code";

        //test for empty fields
        $email_message->requiredFieldsSet();

        return $this->put($this->getBaseUrl() . $path, $email_message->toJson())->json();
    }

    /**
     * @param $email_code
     * @return bool
     * @throws SwiftReachException
     */
    public function deleteEmailMessage($email_code)
    {
        $url = $this->base_url . "/api/Messages/Email/Delete/" . $email_code;

        return $this->delete($url);
    }

    public function sendAlertToContacts(ContactArray $contacts, $voice_code = 0, $sms_code = 0, $email_code = 0)
    {
        $url_parts = [
            'TaskName'  => uniqid(),
            'VoiceCode' => $voice_code,
            'FaxCode'   => 0,
            'SMSCode'   => $sms_code,
            'EmailCode' => $email_code,
            'PagerCode' => 0,
        ];

        $url = '/api/Alerts/Send/' . implode('/', $url_parts);

        return $this->post($this->getBaseUrl() . $url, $contacts->toJson())->json();
    }

    public function sentEmailToArrayOfConatcts(EmailMessage $email_message, ContactArray $contacts)
    {
        if (!$email_message->getEmailCode()) {
            throw new SwiftReachException("No Email Code was set.");
        }
        if (!$email_message->getName()) {
            throw new SwiftReachException("The email name wasn't set.");
        }
        $url_parts = array(
            $this->getBaseUrl(),
            "api/Messages/Email/Send",
            rawurlencode($email_message->getName()),
            rawurlencode($email_message->getEmailCode())
        );
        $url = implode('/', $url_parts);
        return $this->post($url, $contacts->toJson());
    }
    public function getEmailMessage($email_code)
    {
        $a = $this->get($this->getBaseUrl()."/api/Messages/Email/".$email_code)->json();
        if(is_null($a)){
            throw new SwiftReachException("Unable to retrieve an email with the code: '$email_code'");
        }
        $em = new EmailMessage();
        $em->populateFromArray($a);
        return $em;
    }

    public function textToEmailSourceArrayHelper($text)
    {
        $url = $this->getBaseUrl() . "/api/Messages/Email/Helpers/TextToEmailSourceObject";
        $textSources = $this->post($url, trim(json_encode([$text => '']),"{}"))->json();

        $emailMessage = New EmailContent();
        $emailMessage->populateFromArray(["Body" => $textSources]);

        return $emailMessage->getBody();
    }

    //-----------------------------------------------------------------------------------------------------------------
    //  End Email
    //-----------------------------------------------------------------------------------------------------------------

    //-----------------------------------------------------------------------------------------------------------------
    //  Start Voice Functions
    //-----------------------------------------------------------------------------------------------------------------

    /**
     * Create a simple voice message on the swift reach system.
     * @param SimpleVoiceMessage $message
     * @return MessageProfile
     * @throws \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function createSimpleVoiceMessage(SimpleVoiceMessage $message)
    {
        $path = "/api/Messages/Voice/Create/Simple";

        //test for empty fields
        $message->requiredFieldsSet();

        $response = $this->post($this->getBaseUrl() . $path, $message->toJson());
        $message_profile = new MessageProfile();
        $message_profile->populateFromArray($response->json());

        return $message_profile;
    }

    /**
     * Create a voice message on the swift reach system.
     * @param AbstractVoiceMessage $message
     * @return MessageProfile
     * @throws \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function createVoiceMessage(AbstractVoiceMessage $voice_message)
    {
        $path = "/api/Messages/Voice/Create";

        //test for empty fields
        $voice_message->requiredFieldsSet();

        $json = $voice_message->toJson();
        $response = $this->post($this->getBaseUrl() . $path, $json);

        $json = $response->json();
        if (!isset($json["VoiceCode"])) {
            throw new SwiftReachException("Failed to create a new voice message");
        }
        $message_profile = new MessageProfile();
        $message_profile->populateFromArray($json);

        return $message_profile;
    }

    public function sendSimpleVoiceMessageToContactArray($message, ContactArray $contacts, $hotline = '')
    {
        return $this->sendMessageToContactArray($message, $contacts, $hotline);
    }

    public function sendMessageToContactArray(AbstractVoiceMessage $message, ContactArray $contacts, $hotline = '')
    {
        if (!$message->getVoiceCode()) {
            throw new SwiftReachException("No Voice Code was set.");
        }

        // without a matching name it won't display the caller id number
        // when the call is made, but the call will still go through
        if (!$message->getName()) {
            throw new SwiftReachException("The message name was not set or was blank.");
        }

        if ($hotline && preg_match('/[^0-9]/', $hotline) && strlen($hotline) != 10) {
            throw new SwiftReachException("Hotline numbers must be a valid 10 digit phone number");
        }

        $url_parts = array();

        if ($hotline) {
            $url_parts[] = "Publish";
            $url_parts[] = rawurlencode($message->getName());
            $url_parts[] = rawurlencode($message->getVoiceCode());
            $url_parts[] = rawurlencode($hotline);
        } else {
            $url_parts[] = rawurlencode($message->getName());
            $url_parts[] = rawurlencode($message->getVoiceCode());
        }

        $url = $this->getBaseUrl() . "/api/Messages/Voice/Send/" . implode("/", $url_parts);

        $response = $this->post($url, $contacts->toJson());

        // response body is the job id
        return $response->getBody();
    }

    /**
     * @param $voice_code
     * @return bool
     * @throws SwiftReachException
     */
    public function deleteVoiceMessage($voice_code)
    {
        $url = $this->base_url . "/api/Messages/Voice/Delete/" . $voice_code;

        return $this->delete($url);
    }

    public function getHotlineList()
    {
        return $this->get($this->getBaseUrl() . "/api/Hotlines/List")->json();
    }


    /**
     * @param $voice_code
     * @return \SwiftReachApi\Voice\MessageProfile
     */
    public function getMessageProfile($voice_code)
    {
        $json = $this->get($this->getBaseUrl() . "/api/Messages/Voice/" . $voice_code)->json();
        if (is_null($json)) {
            throw new SwiftReachException("Couldn't find a message profile with the voice code '" . $voice_code . "'");
        }
        // cast to correct voice type
        $mp = $this->createMessageProfileByVoiceType($json["VoiceType"]);
        $mp->populateFromArray($json);

        return $mp;
    }


    private function createMessageProfileByVoiceType($voice_type)
    {
        switch($voice_type)
        {
            case AbstractVoiceMessage::VOICE_TYPE_VOICE_MESSAGE:
            case 14:
                return new VoiceMessage();
                break;
        }
    }

    public function createContentProfileHelper($text, $item_type = VoiceAlertContent::ALERT_HUMAN,  $use_tts = true)
    {
        $url = $this->getBaseUrl() . "/api/Messages/Voice/Helpers/TextToVoiceContentObject/$item_type/";
        $url .= ($use_tts ? "true" : "false");

        $json = $this->post($url, trim(json_encode([$text => '']),"{}"))->json();
        $vac = new VoiceAlertContent();
        $vac->populateFromArray($json);

        return $vac;
    }

    public function updateVoiceMessage($voice_code, VoiceMessage $voice_message)
    {
        if(!$voice_message->getVoiceCode()) {
            throw new SwiftReachException('Voice code is required in the body of the API');
        }

        $url = $this->getBaseUrl() . "/api/Messages/Voice/Update/".$voice_code;
        $json = $this->put($url, $voice_message->toJson())->json();

        return $json;
    }

    //-----------------------------------------------------------------------------------------------------------------
    //  End Voice
    //-----------------------------------------------------------------------------------------------------------------

    //-----------------------------------------------------------------------------------------------------------------
    //  Start sms Functions
    //-----------------------------------------------------------------------------------------------------------------
    public function createSmsMessage(SmsMessage $sms_message)
    {
        $url = $this->getBaseUrl() . "/api/Messages/Text/Create";
        $json = $this->post($url, $sms_message->toJson())->json();

        return $json;
    }

    public function getSmsMessage($sms_code)
    {
        $url = $this->getBaseUrl() . "/api/Messages/Text/".$sms_code;
        $json = $this->get($url)->json();

        return $json;
    }

    public function updateSmsMessage($sms_code, SmsMessage $sms_message)
    {
        if(!$sms_message->getSmsCode()) {
            throw new SwiftReachException('SMS code is required in the body of the API');
        }

        $url = $this->getBaseUrl() . "/api/Messages/Text/Update/".$sms_code;
        $json = $this->put($url, $sms_message->toJson())->json();

        return $json;
    }

    /**
     * @param $sms_code
     * @return bool
     * @throws SwiftReachException
     */
    public function deleteSmsMessage($sms_code)
    {
        $url = $this->base_url . "/api/Messages/Text/Delete/" . $sms_code;

        return $this->delete($url);
    }

    public function textToSmsSourceArrayHelper($text)
    {
        $url = $this->getBaseUrl() . "/api/Messages/Text/Helpers/TextToSMSSourceObject";
        $textSources = $this->post($url, trim(json_encode([$text => '']),"{}"))->json();
        
        $smsContent = New SmsContent();
        $smsContent->populateFromArray(["Body" => $textSources]);

        return $smsContent->getBody();
    }

    //-----------------------------------------------------------------------------------------------------------------
    //  End sms Functions
    //-----------------------------------------------------------------------------------------------------------------


    /**
     * @param $job_code
     *
     * @return AlertCampaignProgress
     * @throws SwiftReachException
     */
    public function getAlertCampaignProgress($job_code)
    {
        $url = $this->getBaseUrl() . "/api/Alerts/Progress/".$job_code;
        $responseBody = (string)$this->get($url)->getBody();

        if (empty($responseBody)) {
            throw new SwiftReachException('Unable to retrieve progress for job_code: '. $job_code);
        }

        $campaignReport = new AlertCampaignProgress();
        $campaignReport->populateFromArray(json_decode($responseBody));

        return $campaignReport;
    }


    public function downloadCallRecords($job_code, $full_file_path)
    {
        $url = $this->getBaseUrl() . "/api/Alerts/Reports/Voice/Download/".$job_code;
        file_put_contents($full_file_path, $this->get($url)->getBody());
    }

    public function downloadSmsRecords($job_code, $full_file_path)
    {
        $url = $this->getBaseUrl() . "/api/Alerts/Reports/Text/Download/".$job_code;
        file_put_contents($full_file_path, $this->get($url)->getBody());
    }

    public function downloadEmailRecords($job_code, $full_file_path)
    {
        $url = $this->getBaseUrl() . "/api/Alerts/Reports/Email/Download/".$job_code;
        file_put_contents($full_file_path, $this->get($url)->getBody());
    }

    /**
     * @param $url
     * @return bool
     * @throws SwiftReachException
     */
    private function delete($url)
    {
        $headers = array(
            'Content-type: application/json',
            'SwiftAPI-Key: ' . $this->getApiKey(),
            'Accept: application/json',
            'Expect:'
        );
        $curl_opts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => "DELETE",
            CURLOPT_HTTPHEADER     => $headers
        );
        $response = $this->restAction('delete', $url, $curl_opts);
        $body = (string)$response->getBody();

        return ($body === "0");
    }

    /**
     * @param $url
     * @return bool
     * @throws SwiftReachException
     */
    private function put($url, $body)
    {
        $headers = array(
            'Content-type: application/json',
            'SwiftAPI-Key: ' . $this->getApiKey(),
            'Accept: application/json',
            'Expect:'
        );
        $curl_opts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => "PUT",
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers
        );
        return $this->restAction('put', $url, $curl_opts);
    }

    /**
     * @param $voice_code
     * @param $voice_fragment_code
     * @param $file The full file path
     * @param null $file_type
     * @return bool true on success, false on failure
     */
    public function uploadAudioFile($voice_code, $voice_fragment_code, $file, $file_type = null)
    {
        $url = $this->base_url . "/api/Messages/Voice/UploadAudio/$voice_code/$voice_fragment_code";

        if (!empty($file_type)) {
            $url .= "/" . $file_type;
        }

        $headers = array(
            'Content-type: x-www-form-urlencoded',
            'SwiftAPI-Key: ' . $this->getApiKey(),
            'Expect:'
        );
        $curl_opts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => file_get_contents($file),
            CURLOPT_HTTPHEADER     => $headers
        );
        $response = $this->restAction('post', $url, $curl_opts);
        $body = (string)$response->getBody();

        return ($body === "0");
    }


    private function post($url, $body)
    {
        $headers = array(
            'Content-type: application/json',
            'SwiftAPI-Key: ' . $this->getApiKey(),
            'Accept: application/json',
            'Expect:'
        );
        $curl_opts = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers
        );
        return $this->restAction('post', $url, $curl_opts);

    }

    public function get($url)
    {
        $headers = array(
            'Content-type: application/json',
            'SwiftAPI-Key: ' . $this->getApiKey(),
            'Accept: application/json'
        );

        $curl_opts = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers
        );
        return $this->restAction('get', $url, $curl_opts);
    }

    private function restAction($action, $url, $curl_opts)
    {
        $request_options = $this->request_options;
        $request_options['config']['curl'] = $curl_opts;
        try {
            return $this->getGuzzleClient()->$action(
                $url,
                $request_options
            );
        } catch (ClientException $e) {
            $json = json_decode($e->getResponse()->getBody(), true);
            if (isset($json["Message"])) {
                throw new SwiftReachException($json["Message"]);
            } else {
                throw new SwiftReachException($e->getMessage());
            }
        } catch (Exception $e) {
            throw new SwiftReachException($e->getMessage());
        }
    }

   /**
     * @param mixed $base_url
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
        if (!filter_var($base_url, FILTER_VALIDATE_URL)) {
            throw new SwiftReachException("'" . $base_url . "' is not a valid url.");
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        if(!$this->base_url){
            throw new SwiftReachException("Base url was not set.");
        }
        return $this->base_url;
    }

    /**
     * @param mixed $api_key
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        if (!$api_key) {
            throw new SwiftReachException("'" . $api_key . "' is not a valid api key.");
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        if (!$this->api_key) {
            throw new SwiftReachException("Swift Reach Api key was not set.");
        }
        return $this->api_key;
    }

    /**
     * @param array $request_options
     * @return SwiftReachApi
     * @throws SwiftReachException
     */
    public function setRequestOptions(array $request_options)
    {
        $this->request_options = $request_options;
        if (empty($request_options)) {
            throw new SwiftReachException("Defaults array is not a valid array.");
        }
        return $this;
    }

    /**
     * @return array
     * @throws SwiftReachException
     */
    public function getRequestOptions()
    {
        if (empty($this->request_options)) {
            throw new SwiftReachException("Defaults array was not set.");
        }
        return $this->request_options;
    }

    /**
     * @param \GuzzleHttp\Client $guzzle_client
     */
    public function setGuzzleClient($guzzle_client)
    {
        $this->guzzle_client = $guzzle_client;

        return $this;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getGuzzleClient()
    {
        return $this->guzzle_client;
    }

} 
