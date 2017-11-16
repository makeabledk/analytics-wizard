<?php

namespace AnalyticsWizard;

use Google_Client;

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
    public function __construct($credentials, $appName='Analytics API client')
    {
        if ($credentials instanceof Google_Client) {
            $this->client = $credentials;
        }
        else {
            $this->client = new Google_Client();
            $this->client->setApplicationName($appName);
            $this->client->setAuthConfig($credentialsFile);
            $this->client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);            
        }
        

        $this->analytics = new \Google_Service_AnalyticsReporting($this->client);
    }

    /**
     * @param array $reportRequests
     * @return array
     */
    public function fetchReports(array $reportRequests)
    {
        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests($reportRequests);

        $response = $this->analytics->reports->batchGet($body)->getReports();
        $reports = array();

        foreach($response as $report)
        {
            $reports[] = new ReportResponse($report);
        }

        return $reports;
    }

    /**
     * @param \Google_Service_AnalyticsReporting_ReportRequest $reportRequest
     * @return mixed
     */
    public function fetchReport(\Google_Service_AnalyticsReporting_ReportRequest $reportRequest)
    {
        return $this->fetchReports([$reportRequest])[0];
    }

}
