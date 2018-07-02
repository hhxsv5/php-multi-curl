<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

$start = microtime(true);

$mc = new MultiCurl();

for ($i = 0; $i < 100; ++$i) {
    $c4 = new Curl();
    $c4->makeGet('http://www.weather.com.cn/data/cityinfo/101270101.html');

    $c5 = new Curl();
    $c5->makeGet('http://www.weather.com.cn/data/cityinfo/101270401.html');

    $mc->addCurls([$c4, $c5]);
}
$mc->exec();
echo microtime(true) - $start;
foreach ($mc->getCurls() as $curl) {
    var_dump($curl->getResponse()->getBody());
}