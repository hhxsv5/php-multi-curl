PHP Multi-Curl
======

A php wrapper class for multi-curl is used to handle parallel requests efficiently.  

## Requirements

* PHP 5.4 or later
* PHP cURL extension

## Installation via Composer

    ```
    composer require "hhxsv5/php-multi-curl:~1.0" -vvv

    ```
## Usage
 
    ```PHP
    //require '../vendor/autoload.php';
    
    use Hhxsv5\PhpMultiCurl\Curl;
    use Hhxsv5\PhpMultiCurl\MultiCurl;
    
    //single http request
    $c1 = new Curl();
    $c1->makeGet('http://www.weather.com.cn/data/cityinfo/101270101.html');
    var_dump($c1->exec());//get response, OR var_dump($c1->getResponse());
    
    
    //multi http request
    $c2 = new Curl();
    $c2->makeGet('http://www.weather.com.cn/data/cityinfo/101270101.html');
    
    $c3 = new Curl();
    $c3->makeGet('http://www.weather.com.cn/data/cityinfo/101270401.html');
    
    $mc = new MultiCurl();
    
    $mc->addCurls([$c2, $c3]);
    $ret = $mc->exec();
    var_dump($c2->getResponse(), $c3->getResponse());//get response
    
    //reuse $mc
    $c4 = new Curl();
    $c4->makeGet('http://www.weather.com.cn/data/cityinfo/101270101.html');
    
    $c5 = new Curl();
    $c5->makeGet('http://www.weather.com.cn/data/cityinfo/101270401.html');
    
    $mc->addCurls([$c4, $c5]);
    $ret = $mc->exec();
    var_dump($c4->getResponse(), $c5->getResponse());//get response
    ```
