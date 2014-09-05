<?php
/**
 * Created by PhpStorm.
 * User: alai
 * Date: 9/4/14
 * Time: 10:12 AM
 */

namespace SwiftReachApi\Report\Voice;


use SwiftReachApi\Exceptions\SwiftReachException;

class CallReport
{
    const SORT_ASC  = "ASC";
    const SORT_DESC = "DESC";

    /** @var int */
    private $job_code;

    /** @var  \SwiftReachApi\SwiftReachApi */
    private $sra;


    /** @var int */
    private $page_size = 20;

    /** @var int */
    private $current_row = 0;

    /**  @var string  */
    private $sort_field = "BeginStamp";

    /** @var   */
    private $sort_direction = self::SORT_ASC;

    /**
     * @param \SwiftReachApi\SwiftReachApi $sra
     * @param null $job_code
     */
    function __construct(\SwiftReachApi\SwiftReachApi $sra, $job_code = null)
    {
        $this->sra      = $sra;
        if(!is_null($job_code)){
            $this->job_code = $job_code;
        }
    }

    /**
     * @param int $job_code
     * @return CallReport
     */
    public function setJobCode($job_code)
    {
        $this->job_code = $job_code;
        return $this;
    }

    /**
     * @return int
     */
    public function getJobCode()
    {
        return $this->job_code;
    }

    /**
     * @param \SwiftReachApi\SwiftReachApi $sra
     * @return CallReport
     */
    public function setSwiftReachApi($sra)
    {
        $this->sra = $sra;
        return $this;
    }

    /**
     * @return \SwiftReachApi\SwiftReachApi
     */
    public function getSwiftReachApi()
    {
        return $this->sra;
    }

    /**
     * @param int $current_row
     */
    public function setCurrentRow($current_row)
    {
        $this->current_row = $current_row;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentRow()
    {
        return $this->current_row;
    }

    /**
     * @param int $page_size
     * @return CallReport
     */
    public function setPageSize($page_size)
    {
        $this->page_size = $page_size;
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
     * @param mixed $sort_direction
     * @return CallReport
     */
    public function setSortDirection($sort_direction)
    {
        $this->sort_direction = $sort_direction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortDirection()
    {
        return $this->sort_direction;
    }

    /**
     * @param string $sort_field
     * @return CallReport
     */
    public function setSortField($sort_field)
    {
        $this->sort_field = $sort_field;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortField()
    {
        return $this->sort_field;
    }


    /**
     * @return array
     */
    public function getSearchableFields(){
        return array(
            "BeginStamp",
            "Phone",
            "EntityName",
            "EntityGuid",
            "CallResult",
            "ResponseResult",
            "CauseCode",
            "CompletePlayback",
            "DetectedAnsweringMachine",
            "PinValidated"
        );
    }

    /**
     * @param $field
     * @return bool
     * @throws \SwiftReachApi\Exceptions\SwiftReachException
     */
    private function validateField($field){
        $searchable_fields = $this->getSearchableFields();
        if(!in_array($field, $searchable_fields)){
            throw new SwiftReachException("Field '" . $field . "' is not an acceptable search field(" . implode(
                    ",",
                    $searchable_fields
                ));
        }
        return true;
    }

    /**
     * @param $field
     * @param $search_criteria
     * @throws \SwiftReachApi\Exceptions\SwiftReachException
     * @return int
     */
    public function getSearchResultCount($field, $search_criteria)
    {
        $this->validateField($field);

        if(!$this->getJobCode()){
            throw new SwiftReachException("The job code must be set.");
        }

        $url = $this->sra->getBaseUrl()."/api/Alerts/Reports/Voice/Search/Count/".$this->getJobCode()."/".$field."/".rawurlencode($search_criteria);
        try{
            return (string)$this->sra->get($url)->getBody();
        }catch(\Exception $e){
            if($e->getCode() == "404"){
                throw new SwiftReachException("Could not find a job with job code at: " . $url);
            }
        }
    }

    /**
     * @throws \SwiftReachApi\Exceptions\SwiftReachException
     * @return int
     */
    public function getTotalCount()
    {
        if(!$this->getJobCode()){
            throw new SwiftReachException("The job code must be set.");
        }

        $url = $this->sra->getBaseUrl()."/api/Alerts/Reports/Voice/Count/".$this->getJobCode();
        try{
            return (string)$this->sra->get($url)->getBody();
        }catch(\Exception $e){
            if($e->getCode() == "404"){
                throw new SwiftReachException("Could not find a job with job code at: " . $url);
            }
        }
    }

    public function getRecords($search_field = null, $search_criteria = null)
    {
        if (is_null($search_field)) {
            $url = $this->generateRecordListUrl();
        } elseif (!is_null($search_field) && !is_null($search_criteria)) {
            $url = $this->generateFilteredRecordListUrl($search_field, $search_criteria);
        } else {
            throw new SwiftReachException("If you set a search field you must also set the search criteria.");
        }

        try {
            return $this->sra->get($url)->json();
        } catch (\Exception $e) {
            throw new SwiftReachException($e->getMessage());
        }
    }

    private function generateRecordListUrl()
    {
        $params = array(
            "Job code"          => $this->getJobCode(),
            "Current Row"       => $this->getCurrentRow(),
            "Page Size"         => $this->getPageSize(),
            "Sort Field"        => rawurlencode($this->getSortField()),
            "Sorting Direction" => rawurlencode($this->getSortDirection())
        );

        $this->validateRecordListParams($params);

        return $this->sra->getBaseUrl() . "/api/Alerts/Reports/Voice/" . implode("/", $params);
    }

    private function generateFilteredRecordListUrl($search_field, $search_criteria)
    {
        $params = array(
            "Job code"          => $this->getJobCode(),
            "Current Row"       => $this->getCurrentRow(),
            "Page Size"         => $this->getPageSize(),
            "Sort Field"        => rawurlencode($this->getSortField()),
            "Sorting Direction" => rawurlencode($this->getSortDirection()),
            "Search Field"      => $search_field,
            "Search Criteria"   => $search_criteria
        );

        $this->validateRecordListParams($params);

        return $this->sra->getBaseUrl() . "/api/Alerts/Reports/Voice/Search/" . implode("/", $params);
    }

    private function validateRecordListParams($params)
    {
        $empty_fields = array_filter(
            $params,
            function ($a) {
                return ($a === null || $a === '');
            }
        );
        if (!empty($empty_fields)) {
            throw new SwiftReachException("Could not produce the requested records because the following fields were blank: " . implode(
                    ", ",
                    array_keys($empty_fields)
                ));
        }

        return true;
    }

}
