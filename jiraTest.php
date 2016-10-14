<?php
require_once(__DIR__ . '/config.php');

ini_set('max_execution_time', 300);  //300 seconds = 5 minutes, default is 30 seconds (not enough for creating issues)

//create jira instance
$config = array("username" => $config['Jira']['username'], "password" => $config['Jira']['password'],
    "host" => $config['Jira']['host']);
$jira = new Jira($config);

//make sure you have available project in Jira
$projectKey = "DEV";

//message body, don't forget to json_encode before using in jira component
$body = array("fields" => array("project" => array("key" => $projectKey),
    "summary" => "Test: creating issue in Jira",
    "description" => "creating issue from php-jira-rest-client",
    "issuetype" => array("name" => "Bug"),
    "assignee" => array("name" => null),
    "priority" => array("name" => "High"),
));

//You will get true if action was successful if not the error must be logged in Errors folder
echo $jira->createIssue(json_encode($body));