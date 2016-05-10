<?php
class RESTController extends BaseController
{
    public function initialize(){
        $this->initResponse();
        $this->setFormat("json");
        if($this->request->get("responetype")=="xml") $this->setFormat("xml");
        parent::initialize();
    }
    protected $statusCode = 200;
    protected $headers    = array();
    protected $payload    = '';
    protected $format     = '';

    /**
     * Getters
     */

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return !empty($this->format) ? $this->format : $this->config->app_format;
    }

    /**
     * Setters
     */
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    public function setHeaders($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    protected function initResponse()
    {
        $this->statusCode = 200;
        $this->headers    = array();
        $this->payload    = '';
    }

    protected function render()
    {
        $format      = $this->getFormat();
        $payload     = $this->getPayload();
        $status      = $this->getStatusCode();
        $description = $this->getResponseDescription($status);
        $headers     = $this->getHeaders();

        switch ($format)
        {
            case 'json':
                $contentType = 'application/json';
                $content     = json_encode($payload);
                break;
            case 'xml':
                $array2xml = new Array2xml();
                $contentType = 'application/xml';
                $content     = $array2xml->convert($payload);
                break;
            default:
                $contentType = 'text/plain';
                $content     = $payload;
                break;
        }
        header( "HTTP/1.1 $status $description" );
        header("Content-Type:$contentType;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers:X-Requested-With');
        // Set the additional headers
        foreach ($headers as $key => $value) {
            header("$key:$value");
        }
        $this->view->disable();
        return $content;
    }
    protected function getResponseDescription($code)
    {
        $codes = array(

            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',

            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',

            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',

            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',

            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );

        $result = (isset($codes[$code])) ?
            $codes[$code]          :
            'Unknown Status Code';

        return $result;
    }

}