<?php

namespace Hhxsv5\PhpMultiCurl;

class Response
{
    protected $id;
    protected $httpCode;
    protected $headers = [];
    protected $body    = '';

    /**
     * [errno, errstr]
     * @var array
     */
    protected $error = [];

    public function __construct($id = null, $httpCode = 0, $body = '', array $headers = [], array $error = [])
    {
        $this->id = $id ?: uniqid('', true);
        $this->httpCode = $httpCode;
        $this->headers = array_change_key_case($headers, CASE_LOWER);
        $this->body = $body;
        $this->error = $error;
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
}