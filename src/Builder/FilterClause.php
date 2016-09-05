<?php

namespace AnalyticsWizard\Builder;


abstract class FilterClause
{

    /**
     * @var array
     */
    protected $filters;

    /**
     * FilterClause constructor.
     *
     * @param array $filters
     */
    public function __construct(array &$filters)
    {
        $this->filters = &$filters;
    }

    /**
     * @param $name
     * @param $operator
     * @param $expression
     * @return array
     */
    protected function normalize($name, $operator, $expression)
    {
        if($expression === null) {
            $expression = $operator;
            $operator = 'REGEXP';
        }

        if(!is_array($expression)) {
            $expression = array($expression);
        }

        return [$name, $operator, $expression];
    }

    /**
     * @param $name
     * @param $operator
     * @param null $expression
     * @return mixed
     */
    abstract public function where($name, $operator, $expression=null);

}