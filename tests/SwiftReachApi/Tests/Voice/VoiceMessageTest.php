<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/20/14
 * Time: 11:39 AM
 */

namespace SwiftReachApi\Tests\Voice;


use SwiftReachApi\SwiftReachApi;
use SwiftReachApi\Voice\VoiceContact;
use SwiftReachApi\Voice\VoiceContactPhone;
use SwiftReachApi\Voice\VoiceMessage;

class VoiceMessageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var VoiceMessage
     */
    public $vm;

    /**
     * @var SwiftReachApi
     */
    public $sra;


    public function setup()
    {
        $this->sra = new SwiftReachApi("api_key");
        $this->vm = new VoiceMessage();
    }

    public function testAccessAutoReplays()
    {
        $replays = 1;
        $this->assertEquals($replays, $this->vm->setAutoReplays($replays)->getAutoReplays());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidAutoReplays()
    {
        $replays = "cat";
        $this->vm->setAutoReplays($replays);
    }

    public function testAccessEnableAnsweringMachineMessage()
    {
        $enabled = false;
        $this->assertEquals($enabled, $this->vm->setEnableAnsweringMachineMessage($enabled)->isEnableAnsweringMachineMessage());
    }

    public function testAccessRequiredResponse()
    {
        $enabled = false;
        $this->assertEquals($enabled, $this->vm->setRequiredResponse($enabled)->isRequiredResponse());
    }

    public function testAccessValidResponses()
    {
        $valid_responses = "a,b,c";
        $this->assertEquals($valid_responses, $this->vm->setValidResponses($valid_responses)->getValidResponses());
    }

    public function testGetVoiceType()
    {
        $this->assertEquals(VoiceMessage::VOICE_TYPE_VOICE_MESSAGE, $this->vm->getVoiceType());
    }

    public function testAccessDefaultSpokenLanguage()
    {
        $language = "Spanish";
        $this->assertEquals($language, $this->vm->setDefaultSpokenLanguage($language)->getDefaultSpokenLanguage());
    }
}
 