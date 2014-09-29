<?php
namespace SwiftReachApi;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Voice\AbstractVoiceMessage;
use SwiftReachApi\Voice\MessageProfile;
use SwiftReachApi\Voice\SimpleVoiceMessage;
use SwiftReachApi\Voice\VoiceContactArray;

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
     * @var \GuzzleHttp\Client
     */
    private $guzzle_client;

    public function __construct($api_key, $base_url = "http://api.v4.swiftreach.com")
    {
        $this->setApiKey($api_key);
        $this->setBaseUrl($base_url);

        $this->guzzle_client = new Client();
    }

    /**
     * Create a simple voice message on the swift reach system.
     * @param SimpleVoiceMessage $message
     * @return SimpleVoiceMessage Return the passed in $message with the VoiceCode set if the request was successful
     * @throws Exceptions\SwiftReachException
     * @throws \Exception
     */
    public function createSimpleVoiceMessage(SimpleVoiceMessage $message)
    {
        $path = "/api/Messages/Voice/Create/Simple";
        if(!$this->getApiKey()){
            throw new SwiftReachException("Swift Reach Api key was not set.");
        }
        if(!$this->getBaseUrl()){
            throw new SwiftReachException("Base url was not set.");
        }

        //test for empty fields
        $fields = array("Name", "Description", "CallerId", "UseTTS", "Content");
        $missing_fields = array();
        foreach ($fields as $field) {
            $func = "get" . $field;
            if ($message->$func() == "") {
                $missing_fields[] = $field;
            }
        }
        if (!empty($missing_fields)) {
            throw new SwiftReachException("The following fields cannot be blank: " . implode(", ", $missing_fields));
        }


        try{
            $response = $this->post($this->getBaseUrl().$path, $message->toJson());
        }
        catch(\Exception $e){
            throw $e;
        }

        $json = $response->json();
        $message->setVoiceCode($json["VoiceCode"]);

        return $message;
    }

    public function createVoiceMessage(AbstractVoiceMessage $voice_message)
    {
        $path = "/api/Messages/Voice/Create";
        if(!$this->getApiKey()){
            throw new SwiftReachException("Swift Reach Api key was not set.");
        }
        if(!$this->getBaseUrl()){
            throw new SwiftReachException("Base url was not set.");
        }

        //test for empty fields
        $fields = array("Name", "Description", "CallerId");
        $missing_fields = array();
        foreach ($fields as $field) {
            $func = "get" . $field;
            if ($voice_message->$func() == "") {
                $missing_fields[] = $field;
            }
        }
        if (!empty($missing_fields)) {
            throw new SwiftReachException("The following fields cannot be blank: " . implode(", ", $missing_fields));
        }


        try{
            $json = $voice_message->toJson();
            $response = $this->post($this->getBaseUrl().$path, $json);
        }
        catch(\Exception $e){
            throw $e;
        }


        $json = $response->json();
        if(!isset($json["VoiceCode"])){
            throw new SwiftReachException("Failed to create a new voice message");
        }

        $voice_message->setVoiceCode($json["VoiceCode"]);



        return $voice_message;
    }

    public function sendSimpleVoiceMessageToContactArray(SimpleVoiceMessage $message, VoiceContactArray $contacts, $hotline = '')
    {
        if(!$message->getVoiceCode()){
            throw new SwiftReachException("No Voice Code was set.");
        }

        // without a matching name it won't display the caller id number
        // when the call is made, but the call will still go through
        if(!$message->getName()){
            throw new SwiftReachException("The message name was not set or was blank.");
        }

        if($hotline && preg_match('/[^0-9]/',$hotline) && strlen($hotline) != 10){
            throw new SwiftReachException("Hotline numbers must be a valid 10 digit phone number");
        }

        $url_parts = array();

        if($hotline){
            $url_parts[] = "Publish";
            $url_parts[] = rawurlencode($message->getName());
            $url_parts[] = rawurlencode($message->getVoiceCode());
            $url_parts[] = rawurlencode($hotline);
        }else{
            $url_parts[] = rawurlencode($message->getName());
            $url_parts[] = rawurlencode($message->getVoiceCode());
        }

        $url = $this->getBaseUrl()."/api/Messages/Voice/Send/".implode("/", $url_parts);

        $response = $this->post($url, $contacts->toJson());

        // response body is the job id
        return $response->getBody();
    }

    private function post($url, $body)
    {
        $headers = array(
            'Content-type: application/json',
            'SwiftAPI-Key: ' . $this->getApiKey(),
            'Accept: application/json',
            'Expect:'
        );

        try{
            return $this->getGuzzleClient()->post($url, array(
                    'config' => array(
                        'curl' => array(
                            CURLOPT_FOLLOWLOCATION => TRUE,
                            CURLOPT_RETURNTRANSFER => TRUE,
                            CURLOPT_POST => TRUE,
                            CURLOPT_POSTFIELDS => $body,
                            CURLOPT_HTTPHEADER => $headers
                        )
                    )
            ));
        }
        catch(ClientException $e){
            $json = $e->getResponse()->json();
            if(isset($json["Message"])){
                throw new SwiftReachException($json["Message"]);
            }else{
                throw new SwiftReachException($e->getMessage());
            }
        }catch(Exception $e){
            throw new SwiftReachException($e->getMessage());
        }
    }

    public function get($url)
    {
        $headers = array(
            'Content-type: application/json',
            'SwiftAPI-Key: ' . $this->getApiKey(),
            'Accept: application/json'
        );

        try{

            return $this->getGuzzleClient()->get($url, array(
                    'config' => array(
                        'curl' => array(
                            CURLOPT_RETURNTRANSFER => TRUE,
                            CURLOPT_HTTPHEADER => $headers
                        )
                    )
                ));
        }catch(ClientException $e){
            $json = $e->getResponse()->json();
            if(isset($json["Message"])){
                throw new SwiftReachException($json["Message"]);
            }else{
                throw new SwiftReachException($e->getMessage());
            }
        }catch(Exception $e){
            throw new SwiftReachException($e->getMessage());
        }
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
        if(is_null($json)){
            throw new SwiftReachException("Couldn't find a message profile with the voice code '".$voice_code."'");
        }

        $mp = new MessageProfile();
        $mp->populateFromArray($json);
        return $mp;
    }

    /**
     * @param mixed $base_url
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
        if(!filter_var($base_url, FILTER_VALIDATE_URL)){
            throw new SwiftReachException("'".$base_url."' is not a valid url.");
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param mixed $api_key
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        if(!$api_key){
            throw new SwiftReachException("'".$api_key."' is not a valid api key.");
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->api_key;
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