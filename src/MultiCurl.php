<?php

namespace Hhxsv5\PhpMultiCurl;

class MultiCurl extends BaseCurl
{
    /**
     * @var Curl[]
     */
    protected $curls = [];

    protected function init(array $options = [])
    {
        $this->handle = curl_multi_init();

        if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            foreach ($options as $option => $value) {
                curl_multi_setopt($this->handle, $option, $value);
            }
        }
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
            if (!$this->addCurl($curl)) {
                return false;
            }
        }
        return true;
    }

    public function exec($selectTimeout = 1.0)
    {
        if (count($this->curls) == 0) {
            return false;
        }

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


        //clean to re-exec && check success
        $success = true;
        foreach ($this->curls as $curl) {
            $handle = $curl->getHandle();
            $errno = curl_errno($handle);
            $error = curl_error($handle);//Fix: curl_errno() always return 0 when fail
            if ($errno || $error) {
                $curl->setError([$errno, $error]);
                $success = false;
            }

            curl_multi_remove_handle($this->handle, $curl->getHandle());
        }
        $this->curls = [];

        return $success;
    }

    protected function hasError()
    {
        //TODO: implements for PHP 7.1+
        //check curl_multi_errno($this->handle)
        return false;
    }

    public function __destruct()
    {
        curl_multi_close($this->handle);
    }
}