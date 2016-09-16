<?php

namespace AnalyticsWizard;


use Carbon\Carbon;

class ReportRequest
{
    /**
     * @var
     */
    protected static $defaultViewId;

    /**
     * @var \Google_Service_AnalyticsReporting_ReportRequest
     */
    protected $reportRequest;

    /**
     * @var array
     */
    protected $dateRanges;

    /**
     * @var array
     */
    protected $metrics = array();

    /**
     * @var array
     */
    protected $metricFilterClauses = array();

    /**
     * @var array
     */
    protected $dimensions = array();

    /**
     * @var array
     */
    protected $dimensionFilterClauses = array();

    /**
     * @var array
     */
    protected $orderBys = array();

    /**
     * @param $viewId
     */
    public static function setDefaultViewId($viewId)
    {
        self::$defaultViewId = $viewId;
    }

    /**
     * @param null $viewId
     */
    public function __construct($viewId=null)
    {
        $this->reportRequest = new \Google_Service_AnalyticsReporting_ReportRequest;
        $this->reportRequest->setViewId(
            ($viewId === null? self::$defaultViewId : $viewId)
        );
    }

    /**
     * @param $func
     * @param $args
     * @return $this
     */
    public function __call($func, $args)
    {
        call_user_func_array([$this->reportRequest, $func], $args);

        return $this;
    }

    /**
     * @param $property
     * @param $value
     * @return $this
     */
    public function add($property, $value)
    {
        $this->{$property}[] = $value;

        return $this;
    }

    /**
     * @return \Google_Service_AnalyticsReporting_ReportRequest
     */
    public function get()
    {
        $this->reportRequest->setDateRanges($this->dateRanges);
        $this->reportRequest->setMetrics($this->metrics);
        $this->reportRequest->setMetricFilterClauses($this->metricFilterClauses);
        $this->reportRequest->setDimensions($this->dimensions);
        $this->reportRequest->setDimensionFilterClauses($this->dimensionFilterClauses);
        $this->reportRequest->setOrderBys($this->orderBys);

        return $this->reportRequest;
    }

    /**
     * @param $pageToken
     * @return $this
     */
    public function setPage($pageToken)
    {
        $this->reportRequest->setPageToken($pageToken);

        return $this;
    }

    // _________________________________________________________________________________________________________________

    /**
     * @param $from
     * @param $to null
     * @return $this
     */
    public function addDateRange($from, $to=null)
    {
        list($from, $to) = $this->normalizeDates($from, $to);

        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($from);
        $dateRange->setEndDate($to);

        return $this->add('dateRanges', $dateRange);
    }

    /**
     * @param $expression
     * @param null $alias
     * @return $this
     */
    public function addMetric($expression, $alias=null)
    {
        $metric = new \Google_Service_AnalyticsReporting_Metric();
        $metric->setExpression($expression);

        if($alias !== null) {
            $metric->setAlias($alias);
        }

        return $this->add('metrics', $metric);
    }

    /**
     * @param $operator
     * @param \Closure $closure
     * @return $this
     */
    public function addMetricFilterClause($operator, \Closure $closure)
    {
        $filters = array();
        $closure(new Builder\MetricFilterClause($filters));

        $filterClause = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
        $filterClause->setFilters($filterClause);
        $filterClause->setOperator($operator);

        return $this->add('metricFilterClauses', $filterClause);
    }

    /**
     * @param $name
     * @return $this
     */
    public function addDimension($name)
    {
        $dimension = new \Google_Service_AnalyticsReporting_Dimension();
        $dimension->setName($name);

        return $this->add('dimensions', $dimension);
    }

    /**
     * @param $operator
     * @param \Closure $closure
     * @return $this
     */
    public function addDimensionFilterClause($operator, \Closure $closure)
    {
        $filters = array();
        $closure(new Builder\DimensionFilterClause($filters));

        $filterClause = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
        $filterClause->setFilters($filters);
        $filterClause->setOperator($operator);

        return $this->add('dimensionFilterClauses', $filterClause);
    }

    /**
     * @param $field
     * @param null $order
     * @param null $comparison
     * @return $this
     */
    public function addOrderBy($field, $order=null, $comparison=null)
    {
        $orderBy = new \Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName($field);

        if($order !== null) {
            $orderBy->setSortOrder(
                str_replace(['asc', 'desc'], ['ASCENDING', 'DESCENDING'], strtolower($order))
            );
        }

        if($comparison !== null) {
            $orderBy->setOrderType($comparison);
        }

        return $this->add('orderBys', $orderBy);
    }

    // _________________________________________________________________________________________________________________

    /**
     * @param $from
     * @param $to
     * @return array
     */
    private function normalizeDates($from, $to)
    {
        // If $to is empty, use $from date
        $to = ($to===null? $from : $to);

        return [
            $this->normalizeDate($from),
            $this->normalizeDate($to)
        ];
    }

    /**
     * @param $date
     * @return mixed
     */
    private function normalizeDate($date)
    {
        if($date instanceof Carbon) {
            return $date->toDateString();
        }

        return $date;
    }
}