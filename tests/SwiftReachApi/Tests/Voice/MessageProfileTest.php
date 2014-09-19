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
            "AutoRetries" => 5,
            "AutoRetriesInterval" => 50,
            "CapacityLimit" => 50,
            "CongestionAttempts" => 3,
            "RingSeconds"   => 20,
            "VoiceCode"     => 123456789,
            "CreatedByUser" => "test_user",
            "ChangedByUser" => "test_user",
            "LastUsed"      => "0001-01-01T00:00:00",
            "CreateStamp"      => "0001-01-01T00:00:00",
            "ChangeStamp"      => "0001-01-01T00:00:00",
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

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidVisiblity()
    {
        $this->mp->setVisibility('invalid-visibility');
    }

    public function testAcceessVoiceType()
    {
        $type = "test_type";
        $this->assertEquals($type, $this->mp->setVoiceType($type)->getVoiceType());
    }
}
 