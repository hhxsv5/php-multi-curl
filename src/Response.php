<?php

namespace Hhxsv5\PhpMultiCurl;

class Response
{
    protected $url;
    protected $httpCode;
    protected $headers = [];
    protected $body    = '';

    /**
     * [errno, errstr]
     * @var array
     */
    protected $error = [];

    public function __construct($url = null, $httpCode = 0, $body = '', array $headers = [], array $error = [])
    {
        $this->url = $url;
        $this->httpCode = $httpCode;
        $this->headers = array_change_key_case($headers, CASE_LOWER);
        $this->body = $body;
        $this->error = $error;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function getHeader($key)
    {
        $key = strtolower($key);
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function hasError()
    {
        return !empty($this->error[0]) || !empty($this->error[1]);
    }

    public function getError()
    {
        return $this->error;
    }

    public function __toString()
    {
        return $this->body;
    }

    public static function parse($str)
    {
        $headers = [];
        list($header, $body) = explode("\r\n\r\n", $str, 2);
        $data = explode("\n", $header);
        array_shift($data);//Remove status

        foreach ($data as $part) {
            $middle = explode(':', $part);
            $headers[trim($middle[0])] = trim($middle[1]);
        }
        return [$headers, $body];
    }

    public static function make($url, $code, $responseStr, $errno, $errstr)
    {
        $error = [];
        if ($errno || $errstr) {
            $headers = [];
            $body = '';
            $error = [$errno, $errstr];
        } else {
            list($headers, $body) = static::parse($responseStr);
        }
        return new static($url, $code, $body, $headers, $error);
    }
}