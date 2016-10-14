<?php
require_once(__DIR__ . '/JiraComponent/Jira.php');
require_once(__DIR__ . '/JiraComponent/RestRequest.php');
//  ****** JIRA CONFIGURATIONS ******
//  This part is for Jira login authentication
//  Make sure your Jira user has rights to perform specific actions in Jira
//  For host write a full address e.g. http://jira.com
$config['Jira']['username'] = 'test1';
$config['Jira']['password'] = 'test1';
$config['Jira']['host'] = 'http://localhost:8080';

