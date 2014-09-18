<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/17/14
 * Time: 5:09 PM
 */

namespace Report\Voice;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;

use SwiftReachApi\Report\Voice\CallReport;
use SwiftReachApi\SwiftReachApi;
use SwiftReachApi\Voice\SimpleVoiceMessage;

class CallReportTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var SwiftReachApi
     */
    public $sra;

    /**
     * @var \SwiftReachApi\Voice\SimpleVoiceMessage
     */
    public $svm;

    /** @var int  */
    public $job_code = 654321;

    /**
     * @var \SwiftReachApi\Report\Voice\CallReport
     */
    public $call_report;


    public function setup()
    {
        $this->sra = new SwiftReachApi("api-key");

        $this->svm = new SimpleVoiceMessage();
        $a = array(
            "Name"          => "API test message",
            "Description"   => "description",
            "CallerID"      => 1234567890,
            "UseTTS"        => true,
            "Content"       => "content that must be at least 10 characters long.",
            "VoiceCode"     => 987654,
        );

        $this->svm->setName($a["Name"])
            ->setDescription($a["Description"])
            ->setCallerId($a["CallerID"])
            ->setUseTTS($a["UseTTS"])
            ->setContent($a["Content"])
            ->setVoiceCode($a["VoiceCode"]);


        $this->call_report = new CallReport($this->sra);
    }

    public function testConstructor()
    {
        $call_report = new CallReport($this->sra);
        $this->assertEquals($this->sra, $call_report->getSwiftReachApi());

        $job_code = "654123";
        $call_report = new CallReport($this->sra, $job_code);
        $this->assertEquals($job_code, $call_report->getJobCode());
    }

    public function testAccessJobCode()
    {
        $job_code = 123456;
        $this->call_report->setJobCode($job_code);
        $this->assertEquals($job_code, $this->call_report->getJobCode());
    }

    public function testAccessSwiftReachApi()
    {
        $this->call_report->setSwiftReachApi($this->sra);
        $this->assertEquals($this->sra, $this->call_report->getSwiftReachApi());
    }

    public function testAccessCurrentPage()
    {
        $page = 5;
        $this->call_report->setCurrentPage($page);
        $this->assertEquals($page, $this->call_report->getCurrentPage());
    }

    /**
     * @expectedException  \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNegativeCurrentPageException()
    {
        $page = -5;
        $this->call_report->setCurrentPage($page);
    }

    public function testAccessPageSize()
    {
        $page_size = 5;
        $this->call_report->setPageSize($page_size);
        $this->assertEquals($page_size, $this->call_report->getPageSize());
    }

    /**
     * @expectedException  \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNegativePageSizeException()
    {
        $page_size = -5;
        $this->call_report->setPageSize($page_size);
    }

    public function testAccessSortDirection()
    {
        $this->call_report->setSortDirection(CallReport::SORT_ASC);
        $this->assertEquals(CallReport::SORT_ASC, $this->call_report->getSortDirection());
    }
    public function testAccessSortField()
    {
        $field = "Name";
        $this->call_report->setSortField($field);
        $this->assertEquals($field, $this->call_report->getSortField());
    }

    public function testGetSearchableFields()
    {
        $this->assertTrue(is_array($this->call_report->getSearchableFields()));
    }

    /**
     * @expectedException  \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testValidValidateFields()
    {
        $invalid_field = "non-existent field";
        $this->call_report->getSearchResultCount($invalid_field, "test");
    }


    public function testSearchResultCount()
    {
        $count = "20";
        $mock   = new Mock(
            array(
                new Response(200, array(), Stream::factory($count))
            )
        );
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);

        $fields = $this->call_report->getSearchableFields();
        $this->call_report->setJobCode(12345);
        $this->assertEquals($count, $this->call_report->getSearchResultCount(array_pop($fields), "test"));
    }
    /**
     * @expectedException  \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNoJobCodeSearchResultCount()
    {
        $call_report = new CallReport($this->sra);
        $fields = $call_report->getSearchableFields();
        $call_report->getSearchResultCount(array_pop($fields), "test");
    }

    public function testgetTotalCount()
    {
        $count = "20";
        $mock   = new Mock(
            array(
                new Response(200, array(), Stream::factory($count))
            )
        );
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);

        $fields = $this->call_report->getSearchableFields();
        $this->call_report->setJobCode(12345);
        $this->assertEquals($count, $this->call_report->getTotalCount(array_pop($fields), "test"));
    }
    /**
     * @expectedException  \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testNoJobCodeTotalCount()
    {
        $call_report = new CallReport($this->sra);
        $fields = $call_report->getSearchableFields();
        $call_report->getTotalCount(array_pop($fields), "test");
    }

    //-----------------------------------------------------------------
    // Test Get Records
    //-----------------------------------------------------------------

    public function testNoSearchOptionsGetRecords()
    {
        $mock   = new Mock(
            array(
                new Response(200, array(), Stream::factory('{}'))
            )
        );
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);

        $this->call_report->setJobCode(12345);
        $result = $this->call_report->getRecords();
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));
    }

    /**
     * @expectedException  \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidRecordParamsGetRecords()
    {
        $mock   = new Mock(
            array(
                new Response(200, array(), Stream::factory('{}'))
            )
        );
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);

        $this->call_report->setJobCode(12345);
        $this->call_report->setSortDirection('');
        $result = $this->call_report->getRecords();
    }

    public function testWithSearchOptionsGetRecords()
    {
        $mock   = new Mock(
            array(
                new Response(200, array(), Stream::factory('{}'))
            )
        );
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);

        $this->call_report->setJobCode(12345);
        $fields = $this->call_report->getSearchableFields();
        $result = $this->call_report->getRecords(array_pop($fields), CallReport::SORT_ASC);
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));
    }

    /**
     * @expectedException  \SwiftReachApi\Exceptions\SwiftReachException
    */
    public function testMissingSearchCriteriaGetRecords()
    {
        $mock   = new Mock(
            array(
                new Response(200, array(), Stream::factory('{}'))
            )
        );
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);

        $this->call_report->setJobCode(12345);
        $this->call_report->setSortDirection('');
        $fields = $this->call_report->getSearchableFields();
        $result = $this->call_report->getRecords(array_pop($fields), null);
    }

    /**
     * @expectedException  \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testInvalidFieldGetRecords()
    {
        $mock   = new Mock(
            array(
                new Response(200, array(), Stream::factory('{}'))
            )
        );
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);

        $this->call_report->setJobCode(12345);
        $this->call_report->setSortDirection('');
        $result = $this->call_report->getRecords('non-existent-field', '');
    }

    /**
     * @expectedException \SwiftReachApi\Exceptions\SwiftReachException
     */
    public function testExceptionGetRecords()
    {
        $mock   = new Mock();
        $mock->addException(new RequestException("failed", new Request('GET', 'http://example.com')));
        $client = new Client();
        $client->getEmitter()->attach($mock);
        $this->sra->setGuzzleClient($client);

        $this->call_report->setJobCode(12345);
        $fields = $this->call_report->getSearchableFields();
        $result = $this->call_report->getRecords(array_pop($fields), "search");
    }
}
 