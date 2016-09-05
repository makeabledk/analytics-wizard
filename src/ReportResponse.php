<?php

namespace AnalyticsWizard;


class ReportResponse
{

    /**
     * @var \Google_Service_AnalyticsReporting_Report
     */
    protected $report;

    /**
     * Response constructor.
     *
     * @param \Google_Service_AnalyticsReporting_Report $report
     */
    public function __construct(\Google_Service_AnalyticsReporting_Report $report)
    {
        $this->report = $report;
    }

    /**
     * @return \Google_Service_AnalyticsReporting_Report
     */
    public function get()
    {
        return $this->report;
    }

}