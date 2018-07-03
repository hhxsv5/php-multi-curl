<?php

namespace Hhxsv5\PhpMultiCurl;

class Curl
{
    use ResponseParser;

    protected $id;
    protected $handle;

    /**
     * @var Response
     */
    protected $response;

    protected $multi = false;

    protected static $defaultOptions = [
        //bool
        CURLOPT_HEADER         => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,

        //int
        CURLOPT_MAXREDIRS      => 3,
        CURLOPT_TIMEOUT        => 6,
        CURLOPT_CONNECTTIMEOUT => 3,

        //string
        CURLOPT_USERAGENT      => 'Multi-Curl Client v1.5.0',
    ];

    public function __construct($id = null, array $options = [])
    {
        $this->id = $id;
        $this->handle = curl_init();
        $finalOptions = $options + self::$defaultOptions;
        curl_setopt_array($this->handle, $finalOptions);
    }

    public function getId()
    {
        return $this->id;
    }

    public function makeGet($url, $params = null, array $headers = [])
    {
        if (is_string($params) || is_array($params)) {
            is_array($params) AND $params = http_build_query($params);
            $url = rtrim($url, '?');
            if (strpos($url, '?') !== false) {
                $url .= '&' . $params;
            } else {
                $url .= '?' . $params;
            }
        }

        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_HTTPGET, true);//HTTP GET
        $headers AND curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);
    }

    public function makePost($url, $params = null, array $headers = [])
    {
        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_POST, true);//HTTP POST

        //CURLFile support
        if (is_array($params)) {
            $hasUploadFile = false;
            if (version_compare(PHP_VERSION, '5.5.0') >= 0) {//CURLFile: since 5.5.0
                foreach ($params as $k => $v) {
                    if ($v instanceof \CURLFile) {
                        $hasUploadFile = true;
                        break;
                    }
                }
            }
            $hasUploadFile OR $params = http_build_query($params);
        }

        //$params: array => multipart/form-data, string => application/x-www-form-urlencoded
        $params AND curl_setopt($this->handle, CURLOPT_POSTFIELDS, $params);

        $headers AND curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);
    }

    public function exec()
    {
        if ($this->multi) {
            $responseStr = curl_multi_getcontent($this->handle);
        } else {
            $responseStr = curl_exec($this->handle);
        }

        $errno = curl_errno($this->handle);
        $errstr = curl_error($this->handle);//Fix: curl_errno() always return 0 when fail
        $url = curl_getinfo($this->handle, CURLINFO_EFFECTIVE_URL);
        $this->response = $this->toResponse($url, $responseStr, $errno, $errstr);
        return $this->response;
    }

    public function setMulti($isMulti)
    {
        $this->multi = (bool)$isMulti;
    }

    public function responseToFile($filename)
    {
        $folder = dirname($filename);
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        return file_put_contents($filename, $this->getResponse()->getBody());
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function __destruct()
    {
        curl_close($this->handle);
    }
}