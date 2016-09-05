<?php

namespace AnalyticsWizard\Builder;


class MetricFilterClause extends FilterClause
{

    /**
     * @param $name
     * @param $operator
     * @param null $expression
     * @return $this
     */
    public function where($name, $operator, $expression=null)
    {
        list($name, $operator, $expression) = $this->normalize($name, $operator, $expression);

        $filter = new \Google_Service_AnalyticsReporting_MetricFilter();
        $filter->setMetricName($name);
        $filter->setOperator($operator);
        $filter->setComparisonValue($expression);

        $this->filters[] = $filter;

        return $this;
    }

}