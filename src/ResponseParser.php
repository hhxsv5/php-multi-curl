<?php

namespace Hhxsv5\PhpMultiCurl;

trait ResponseParser
{
    public function parse($str)
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

    public function toResponse($id, $responseStr, $errno, $errstr)
    {
        $httpCode = 0;
        $error = [];
        if ($errno || $errstr) {
            $headers = [];
            $body = '';
            $error = [$errno, $errstr];
        } else {
            $httpCode = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
            list($headers, $body) = $this->parse($responseStr);
        }
        return new Response($id, $httpCode, $body, $headers, $error);
    }
}