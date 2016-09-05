<?php

namespace AnalyticsWizard;


class ReportRequest
{
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
     * @param $viewId
     */
    public function __construct($viewId)
    {
        $this->reportRequest = new \Google_Service_AnalyticsReporting_ReportRequest;
        $this->reportRequest->setViewId($viewId);
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

        return $this->reportRequest;
    }

    // _________________________________________________________________________________________________________________

    /**
     * @param $from
     * @param $to
     * @return Report
     */
    public function addDateRange($from, $to)
    {
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($from);
        $dateRange->setEndDate($to);
//        $this->dateRanges = $dateRange;

//        return $this;
        return $this->add('dateRanges', $dateRange);
    }

    /**
     * @param $expression
     * @param null $alias
     * @return Report
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
     * @return Report
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
     * @return Report
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
     * @return Report
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
}