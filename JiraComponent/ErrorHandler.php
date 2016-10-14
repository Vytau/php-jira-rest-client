<?php

class ErrorHandler
{
    private $requestURL;
    private $requestMethod;
    private $requestBody;
    private $date;
    private $responseInfo;
    private $responseError;
    private $responseBody;
    private $extra;

    /**
     * ErrorHandler constructor.
     * @param string $url
     * @param string $method
     * @param string $Body
     * @internal param $action
     */
    public function __construct($url = "url action not defined", $method = "", $Body = "body is not specified")
    {
        $this->requestURL = $url;
        $this->requestMethod = $method;
        $this->requestBody = $Body;
        $this->date = date('Y-m-d H:i:s');
    }

    /**
     * @param mixed $responseInfo
     */
    public function setResponseInfo($responseInfo)
    {
        $this->responseInfo = $responseInfo;
    }

    /**
     * @param mixed $responseError
     */
    public function setResponseError($responseError)
    {
        $this->responseError = $responseError;
    }

    /**
     * @param mixed $responseBody
     */
    public function setResponseBody($responseBody)
    {
        $this->responseBody = $responseBody;
    }

    /**
     * @param mixed $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    private function makeString($data)
    {
        $str = "";
        switch (gettype($data)) {
            case ('string'):
                $str = $data;
                break;
            case ('array'):
                $str = json_encode($data);
                break;
            default:
                $str = "data type is not defined check ErrorHandler.php makeString function";
        }

        return $str;
    }

    public function logErrors(){
        file_put_contents(__DIR__.'/../Errors/errors_'.date('Y-m-d').'.log', (string)$this, FILE_APPEND);
    }

    function __toString()
    {
        $str = "\n**********************************************";
        $str .= "\nURL performed: " . $this->requestURL;
        $str .= "\nMethod: " . $this->requestMethod;
        $str .= "\nDate: " . $this->date;
        $str .= "\nRequest body: " . $this->requestBody;
        if (isset($this->responseBody)) {
            $str .= "\nResponse body: " . $this->makeString($this->responseBody);
        }
        if (isset($this->responseInfo)) {
            $str .= "\nResponse info: " . $this->makeString($this->responseInfo);
        }
        if (isset($this->responseError)) {
            $str .= "\nResponse error: " . $this->makeString($this->responseError);
        }
        if (isset($this->extra)) {
            $str .= "\nResponse extra: " . $this->makeString($this->extra);
        }
        return $str;
    }


}