<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 8/19/14
 * Time: 9:51 AM
 */

namespace SwiftReachApi\Tests\Voice;

use SwiftReachApi\Voice\MessageProfile;
use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Voice\VoiceAlertProfile;

class MessageProfileTest extends \PHPUnit_Framework_TestCase
{
    /** @var  MessageProfile */
    public $mp;
    
    public function setup()
    {
        $this->mp = new MessageProfile();
    }

    public function testAccessors()
    {
        //vals
        $a = array(
            "AutoRetries"         => 5,
            "AutoRetriesInterval" => 50,
            "CapacityLimit"       => 50,
            "CongestionAttempts"  => 3,
            "RingSeconds"         => 20,
            "VoiceCode"           => 123456789,
            "CreatedByUser"       => "test_user",
            "ChangedByUser"       => "test_user",
            "LastUsed"            => "0001-01-01T00:00:00",
            "CreateStamp"         => "0001-01-01T00:00:00",
            "ChangeStamp"         => "0001-01-01T00:00:00",
        );

        foreach($a as $func => $val){
            $get = "get".$func;
            $set = "set".$func;
            $this->assertEquals($val, $this->mp->$set($val)->$get());
        }

        //booleans
        $a = array(
            "DeleteLocked"    => true,
            "EnableAnsweringMachineDetection" => false,
            "EnableWaterFall" => false,
        );
        foreach($a as $func => $val){
            $is = "is".$func;
            $set = "set".$func;
            $this->assertEquals($val, $this->mp->$set($val)->$is());
        }
    }

    public function testAcceessVisiblity()
    {
        $this->mp->setVisibility(MessageProfile::VISIBILITY_TYPE_HIDDEN);
        $this->assertEquals(MessageProfile::VISIBILITY_TYPE_HIDDEN, $this->mp->getVisibility());
    }

    public function testVoiceTypeAccessor()
    {
        $this->assertNull($this->mp->getVoiceType());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidVisiblity()
    {
        $this->mp->setVisibility('invalid-visibility');
    }

    /**
     *  @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testToJson()
    {
        $this->mp->toJson();
    }

    public function testRequiredFieldsSetDoesntThrowAnException()
    {
        $this->mp->requiredFieldsSet();
    }

    public function testPopulateFromArray()
    {
        $message_profile_json = json_decode(file_get_contents(__DIR__."/../../Data/message_profile.json"), true);
        $mp = new MessageProfile();
        $mp->populateFromArray($message_profile_json);

        $special_functions = array(
            "CallerID"                        => "getCalledId",
            "DeleteLocked"                    => "isDeleteLocked",
            "EnableWaterfall"                 => "isEnableWaterfall",
            "EnableAnsweringMachineDetection" => "isEnableAnsweringMachineDetection",
            "ContentProfile"                  => "skipContentProfile", // this is tested elsewhere
            "VoiceType"                       => "skipVoiceType", // this is tested elsewhere
        );

        foreach ($message_profile_json as $key => $value) {
            if (in_array($key, array_keys($special_functions))) {
                $get_method = $special_functions[$key];
            } else {
                $get_method = "get" . $key;
            }

            if (method_exists($mp, $get_method)) {
                $this->assertEquals($mp->$get_method($value), $value);
            }
        }
    }

    public function testAccessContentProfile()
    {
        $vap = new VoiceAlertProfile();
        $this->mp->setContentProfiles($vap);
        $this->assertEquals(1, substr_count(get_class($this->mp->getContentProfiles()),"VoiceAlertProfile"));
    }
}
