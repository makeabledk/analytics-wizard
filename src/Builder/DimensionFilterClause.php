<?php

namespace AnalyticsWizard\Builder;


class DimensionFilterClause extends FilterClause
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

        $filter = new \Google_Service_AnalyticsReporting_DimensionFilter();
        $filter->setDimensionName($name);
        $filter->setOperator($operator);
        $filter->setExpressions($expression);

        $this->filters[] = $filter;

        return $this;
    }

}