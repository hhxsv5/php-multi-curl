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
$response = $c1->exec();
if ($response->hasError()) {
    //Fail
    var_dump($response->getError());
} else {
    //Success
    var_dump($response->getHttpCode(), $response->getBody());
}

//Multi http request
$c2 = new Curl();
$c2->makeGet($getUrl);

$c3 = new Curl();
$c3->makePost($postUrl);

$mc = new MultiCurl();

$mc->addCurls([$c2, $c3]);
$allSuccess = $mc->exec();
if ($allSuccess) {
    //All success
    var_dump($c2->getResponse()->getBody(), $c3->getResponse()->getBody());
} else {
    //Some curls failed
    var_dump($c2->getResponse()->getError(), $c3->getResponse()->getError());
}

//Reuse $mc
$c4 = new Curl();
$c4->makeGet($getUrl);

$c5 = new Curl();
$c5->makePost($postUrl);

$mc->addCurls([$c4, $c5]);
$allSuccess = $mc->exec();
if ($allSuccess) {
    //All success
    var_dump($c4->getResponse()->getBody(), $c5->getResponse()->getBody());
} else {
    //Some curls failed
    var_dump($c4->getResponse()->getError(), $c5->getResponse()->getError());
}
