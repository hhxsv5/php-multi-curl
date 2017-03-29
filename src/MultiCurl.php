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
    }

    public function addCurl(Curl $curl)
    {
        $code = curl_multi_add_handle($this->handle, $curl->getHandle());
        if ($code !== CURLM_OK) {
            return false;
        }
        $curl->setMulti(true);
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

    public function exec($selectTimeout = 1.0)
    {
        if (count($this->curls) == 0) {
            return false;
        }

//        $running = null;
//        do {
//            usleep(100);
//            curl_multi_exec($this->handle, $running);
//        } while ($running > 0);


        // The first curl_multi_select often times out no matter what, but is usually required for fast transfers
        $timeout = 0.001;
        $active = false;
        do {
            while (($mrc = curl_multi_exec($this->handle, $active)) == CURLM_CALL_MULTI_PERFORM) {
                ;
            }
            if ($active && curl_multi_select($this->handle, $timeout) === -1) {
                // Perform a usleep if a select returns -1: https://bugs.php.net/bug.php?id=61141
                usleep(150);
            }
            $timeout = $selectTimeout;
        } while ($active);

        $this->clean();

        return true;
    }

    protected function clean()
    {
        foreach ($this->curls as $curl) {
            curl_multi_remove_handle($this->handle, $curl->getHandle());
        }
        $this->curls = [];
    }

    public function __destruct()
    {
        $this->clean();
        curl_multi_close($this->handle);
    }
}