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

    public static function parse($responseStr, $headerSize)
    {
        $header = substr($responseStr, 0, $headerSize);
        $body = substr($responseStr, $headerSize);
        $lines = explode("\n", $header);
        array_shift($lines);//Remove status

        $headers = [];
        foreach ($lines as $part) {
            $middle = explode(':', $part);
            $key = trim($middle[0]);
            if ($key === '') {
                continue;
            }
            if (isset($headers[$key])) {
                $headers[$key] = (array)$headers[$key];
                $headers[$key][] = isset($middle[1]) ? trim($middle[1]) : '';
            } else {
                $headers[$key] = isset($middle[1]) ? trim($middle[1]) : '';
            }
        }
        return [$headers, $body];
    }

    public static function make($url, $code, $responseStr, $headerSize, array $error)
    {
        if (!empty($error[0]) || !empty($error[1])) {
            $headers = [];
            $body = '';
        } else {
            list($headers, $body) = static::parse($responseStr, $headerSize);
        }
        return new static($url, $code, $body, $headers, $error);
    }
}