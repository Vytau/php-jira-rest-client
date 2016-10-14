<?php

class Jira
{
    private $host;

    public function __construct(array $config = array())
    {
        $this->request = new RestRequest();
        $this->request->username = (isset($config['username'])) ? $config['username'] : null;
        $this->request->password = (isset($config['password'])) ? $config['password'] : null;
        $host = (isset($config['host'])) ? $config['host'] : null;
        $this->host = $host . '/rest/api/2/';

    }

    public function createIssue($json)
    {
        $this->request->openConnect($this->host . 'issue/', 'POST', $json);
        $this->request->execute();

        return $this->request->lastRequestStatus();
    }

    public function addAttachment($fileLocation, $issueKey)
    {
        $this->request->openConnect($this->host . 'issue/' . $issueKey . '/attachments', 'POST', null, $fileLocation);
        $this->request->execute();

        return $this->request->lastRequestStatus();
    }

    public function addComment($json, $issueKey)
    {
        $this->request->openConnect($this->host . 'issue/' . $issueKey . '/comment', 'POST', $json, null);
        $this->request->execute();

        return $this->request->lastRequestStatus();
    }

    //to perform this action jira user requires extra privilege
    public function addWatcher($json, $issueKey)
    {
        $this->request->openConnect($this->host . 'issue/' . $issueKey . '/watchers', 'POST', $json, null);
        $this->request->execute();

        return $this->request->lastRequestStatus();
    }

}