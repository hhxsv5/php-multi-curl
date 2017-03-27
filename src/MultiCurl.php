<?php

namespace Hhxsv5\PhpMultiCurl;

class MultiCurl
{
    protected $handle;
    protected $curls = [];
    protected $contentMap = [];

    public function __construct()
    {
        $this->handle = curl_multi_init();
        $this->curls = [];
    }

    public function addCurl(Curl $curl)
    {
        $code = curl_multi_add_handle($this->handle, $curl->getHandle());
        if ($code !== CURLM_OK) {
            return false;
        }
        $this->curls[] = $curl;
        return true;
    }

    public function addCurls(array $curls)
    {
        foreach ($curls as $curl) {
            $this->addCurl($curl);
        }
        return true;
    }

    public function exec(array &$responses = [])
    {
        if (count($this->curls) == 0) {
            return false;
        }

        $running = null;
        do {
            usleep(1);
            curl_multi_exec($this->handle, $running);
        } while ($running > 0);


//        $active = null;
//        do {
//            $mrc = curl_multi_exec($this->handle, $active);
//            echo '1', time(), PHP_EOL;
//        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//
//        while ($active && $mrc == CURLM_OK) {
//            echo '2', time(), PHP_EOL;
//            if (curl_multi_select($this->handle) != -1) {
//                do {
//                    echo '3', time(), PHP_EOL;
//                    $mrc = curl_multi_exec($this->handle, $active);
//                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//            }
//        }

        foreach ($this->curls as $curl) {
            $responses[] = curl_multi_getcontent($curl->getHandle());
        }
    }

    public function __destruct()
    {
        foreach ($this->curls as $curl) {
            curl_multi_remove_handle($this->handle, $curl->getHandle());
        }
        curl_multi_close($this->handle);
    }
}