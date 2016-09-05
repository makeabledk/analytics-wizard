<?php

namespace AnalyticsWizard;

class Client
{

    /**
     * @var \Google_Client
     */
    protected $client;

    /**
     * @var \Google_Service_AnalyticsReporting
     */
    protected $analytics;

    /**
     * @param $credentialsFile
     * @param string $appName
     */
    public function __construct($credentialsFile, $appName='Analytics API client')
    {
        $this->client = new \Google_Client();
        $this->client->setApplicationName($appName);
        $this->client->setAuthConfig($credentialsFile);
        $this->client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);

        $this->analytics = new \Google_Service_AnalyticsReporting($this->client);
    }

    /**
     * @param $reportRequests
     * @return array
     */
    public function fetchReports($reportRequests)
    {
        if(!is_array($reportRequests)){
            $reportRequests = array($reportRequests);
        }

        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests($reportRequests);

        $response = $this->analytics->reports->batchGet($body);
        $reports = array();

        foreach($response as $report)
        {
            $reports[] = new ReportResponse($report);
        }

        return $reports;
    }

}