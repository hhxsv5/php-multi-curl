<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

//single http request
$c1 = new Curl();
$c1->makeGet('http://www.weather.com.cn/data/cityinfo/101270101.html');
$c1->exec();
var_dump($c1->getResponse());//get response


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
