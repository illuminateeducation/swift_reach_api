<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/18/14
 * Time: 2:39 PM
 */

namespace AudioSource;

use SwiftReachApi\Email\EmailTextSource\TextEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\UserDefinedFieldEmailTextSource;
use SwiftReachApi\Email\EmailTextSource\AutoFieldEmailTextSource;

class AbstractEmailSourceTest extends \PHPUnit_Framework_TestCase
{

    /** @var  TextEmailTextSource */
    protected $text;

    /** @var  UserDefinedFieldEmailTextSource */
    protected $user;

    /** @var  AutoFieldEmailTextSource */
    protected $auto;
    public function setup()
    {
        $this->text = new TextEmailTextSource();
        $this->user = new UserDefinedFieldEmailTextSource();
        $this->auto = new AutoFieldEmailTextSource();
    }

    public function testgetAudioSourceTypes()
    {
        $this->assertTrue(is_array($this->text->getEmailTextSourceTypes()));

        $this->assertTrue(in_array($this->text->getTextType(), $this->text->getEmailTextSourceTypes()));
        $this->assertTrue(in_array($this->user->getTextType(), $this->text->getEmailTextSourceTypes()));
        $this->assertTrue(in_array($this->auto->getTextType(), $this->text->getEmailTextSourceTypes()));
    }
}
 