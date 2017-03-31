<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

$getUrl = 'http://ip.taobao.com/service/getIpInfo.php?ip=myip';
$postUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=yourtoken';

//single http request
$options = [//define the curl options
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERAGENT      => 'Multi-Curl Client V1.0',
];
$c1 = new Curl($options);
$c1->makeGet($getUrl);
$ret = $c1->exec();
var_dump($ret);
if ($ret) {
    //success
    var_dump($c1->getResponse());
} else {
    //fail
    var_dump($c1->getError());
}


//multi http request
$c2 = new Curl();
$c2->makeGet($getUrl);

$c3 = new Curl();
$c3->makePost($postUrl);

$mc = new MultiCurl();

$mc->addCurls([$c2, $c3]);
$ret = $mc->exec();
var_dump($ret);
if ($ret) {
    //success
    var_dump($c2->getResponse(), $c3->getResponse());
} else {
    //execute some curls failed
    var_dump($c2->getError(), $c3->getError());
}

//reuse $mc
$c4 = new Curl();
$c4->makeGet($getUrl);

$c5 = new Curl();
$c5->makePost($postUrl);

$mc->addCurls([$c4, $c5]);
$ret = $mc->exec();
var_dump($ret);
if ($ret) {
    //success
    var_dump($c4->getResponse(), $c5->getResponse());
} else {
    //execute some curls failed
    var_dump($c4->getError(), $c5->getError());
}
