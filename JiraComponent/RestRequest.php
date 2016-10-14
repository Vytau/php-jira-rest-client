<?php

require_once (__DIR__.'/ErrorHandler.php');

class RestRequest
{
    public $username;
    public $password;

    private $url;
    private $verb;
    private $requestBody;
    private $header;
    private $fileLocation;
    private $responseBody;
    private $responseInfo;
    private $responseError;

    private $errorHandler = true;

    public function openConnect($url = null, $verb = "GET", $requestBody = null, $fileLocation = null)
    {
        $this->url = $url;
        $this->verb = $verb;

        //messy header builder need for an improvement
        if ($requestBody != null) {
            $this->requestBody = $requestBody;  //must be json_encoded
            $this->header = array(
                "cache-control: no-cache",
                "content-type: application/json"); //need for creating issue, but blocks uploading the file
        } elseif ($requestBody == null && $fileLocation != null) {
            //php version < 5.5.0 but > 5.2.10 || not tested
//            $data = array('file' => "@" . $fileLocation . ';' .basename($fileLocation));
//            $this->requestBody = json_encode($data);
            //php version >= 5.5.0 || tested and working php version Windows 7.0.9 and 5.5.38
            $cfile = new CURLFile($fileLocation);
            $cfile->setPostFilename(basename($fileLocation));
            $this->requestBody = array('file' => $cfile);
            $this->header = array(
                "cache-control: no-cache",
                "x-atlassian-token: no-check");
        }

        $this->responseBody = null;
        $this->responseInfo = null;
        $this->responseError = null;
    }

    public function execute()
    {
        $curl = curl_init();
        $this->setAuth($curl);
        try {
            switch (strtoupper($this->verb)) {
                case 'GET':
                    $this->executeGet($curl);
                    break;
                case 'POST':
                    $this->executePost($curl);
                    break;
                case 'PUT':
                    $this->executePut($curl);
                    break;
                case 'DELETE':
                    $this->executeDelete($curl);
                    break;
                default:
                    throw new InvalidArgumentException('Current verb (' . $this->verb . ') is an invalid REST verb.');
            }
        } catch (InvalidArgumentException $e) {
            curl_close($curl);
            throw $e;
        } catch (Exception $e) {
            curl_close($curl);
            throw $e;
        }
    }

    public function lastResponseError()
    {
        return $this->responseError;
    }

    public function lastResponseBody()
    {
        return $this->responseBody;
    }

    public function lastResponseInfo()
    {
        return $this->responseInfo;
    }

    public function lastRequestStatus()
    {
        //return true if last API request was successful (when http code >= 200 and < 300)
        $result = $this->responseInfo;
        if (isset($result['http_code']) && ($result['http_code'] >= 200 && $result['http_code'] < 300)) {
            return true;
        } elseif ($this->errorHandler){
            $tmpErr = new ErrorHandler($this->url,$this->verb, $this->requestBody);
            $tmpErr->setResponseBody($this->lastResponseBody());
            $tmpErr->setResponseInfo($this->lastResponseInfo());
            $tmpErr->setResponseError($this->lastResponseError());
            $tmpErr->logErrors();
        }
        return false;
    }

    private function executeGet($curl)
    {
        //ToDo
    }

    private function executePost($curl)
    {
        //set specific curl option values for POST request
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->requestBody
        ));

        $this->doExecute($curl);
    }

    private function executePut($curl)
    {
        //ToDo
    }

    private function executeDelete($curl)
    {
        //ToDo
    }

    private function doExecute(&$curl)
    {
        $this->setCurlOpts($curl);
        //Record curl execution results, info and errors
        $this->responseBody = curl_exec($curl);
        $this->responseInfo = curl_getinfo($curl);
        $this->responseError = curl_error($curl);
        curl_close($curl);
    }

    private function setCurlOpts(&$curl)
    {
        //Set basic curl option values which are applied to all request
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_PORT => "8080",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $this->header,
        ));
    }

    private function setAuth(&$curl)
    {
        if ($this->username !== null && $this->password !== null) {
            curl_setopt($curl, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        }
    }
}