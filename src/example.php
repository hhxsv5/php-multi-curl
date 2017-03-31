<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

$getUrl = 'http://www.weather.com.cn/data/cityinfo/101270101.html';
$postUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=yourtoken';

//Single http request
$options = [//The custom the curl options
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERAGENT      => 'Multi-Curl Client V1.0',
];
$c1 = new Curl($options);
$c1->makeGet($getUrl);
$ret = $c1->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c1->getResponse());
} else {
    //Fail
    var_dump($c1->getError());
}


//Multi http request
$c2 = new Curl();
$c2->makeGet($getUrl);

$c3 = new Curl();
$c3->makePost($postUrl);

$mc = new MultiCurl();

$mc->addCurls([$c2, $c3]);
$ret = $mc->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c2->getResponse(), $c3->getResponse());
} else {
    //Some curls failed
    var_dump($c2->getError(), $c3->getError());
}

//Reuse $mc
$c4 = new Curl();
$c4->makeGet($getUrl);

$c5 = new Curl();
$c5->makePost($postUrl);

$mc->addCurls([$c4, $c5]);
$ret = $mc->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c4->getResponse(), $c5->getResponse());
} else {
    //Some curls failed
    var_dump($c4->getError(), $c5->getError());
}
