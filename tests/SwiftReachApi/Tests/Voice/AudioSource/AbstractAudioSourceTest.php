<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 2:39 PM
 */

namespace AudioSource;

use SwiftReachApi\Voice\AudioSource\TtsFieldAudioSource;

class AbstractAudioSourceTest extends \PHPUnit_Framework_TestCase
{

    /** @var  TtsFieldAudioSource */
    protected $field;
    public function setup()
    {
        $this->field = new TtsFieldAudioSource();
    }

    public function testgetAudioSourceTypes()
    {
        $this->assertTrue(is_array($this->field->getAudioSourceTypes()));
    }
}
 