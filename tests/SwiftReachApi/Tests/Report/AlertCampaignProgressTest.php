<?php
/** @filesource */

namespace SwiftReachApi\Tests\Report;

use SwiftReachApi\Exceptions\SwiftReachException;
use SwiftReachApi\Report\AlertCampaignProgress;

class AlertCampaignProgressTest extends \PHPUnit_Framework_TestCase
{
    /** @var  AlertCampaignProgress */
    private $progressReport;

    public function setup()
    {
        $this->progressReport = new AlertCampaignProgress();
    }

    public function testPopulateByArray()
    {
        $a = json_decode(file_get_contents(__DIR__."/../../Data/Report/alert_campaign_progress.good.json"), true);
        $this->progressReport->populateFromArray($a);


        foreach ($a as $property => $expected_value){
            // this value gets transformed into class constant
            if($property == 'JobState') {
                continue;
            }

            $method = 'get'.$property;

            $this->assertEquals($expected_value, $this->progressReport->$method());
        }

        $this->assertEquals(AlertCampaignProgress::STATE_COMPLETED, $this->progressReport->getJobState());
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidJobState()
    {
        $this->progressReport->intToJobState(8);
    }

    /**
     * @dataProvider jobStateDataProvider
     */
    public function testIntToJobState($input, $expected)
    {
        $this->assertEquals($expected, $this->progressReport->intToJobState($input));
    }

    public function jobStateDataProvider()
    {
        return [
            [0, AlertCampaignProgress::STATE_QUEUED],
            [1, AlertCampaignProgress::STATE_INPROGRESS],
            [2, AlertCampaignProgress::STATE_PAUSED],
            [3, AlertCampaignProgress::STATE_COMPLETED],
        ];
    }
}
