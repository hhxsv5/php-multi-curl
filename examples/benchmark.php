<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

$start = microtime(true);

$mc = new MultiCurl();

for ($i = 0; $i < 200; ++$i) {
    $c4 = new Curl();
    $c4->makeGet('http://www.weather.com.cn/data/cityinfo/101270101.html');

    $c5 = new Curl();
    $c5->makeGet('http://www.weather.com.cn/data/cityinfo/101270401.html');

    $mc->addCurls([$c4, $c5]);
}
$mc->exec();

echo sprintf('Total cost: %fs', microtime(true) - $start), PHP_EOL;

foreach ($mc->getCurls() as $curl) {
    $response = $curl->getResponse();
    if ($response->hasError()) {
        var_dump($response->getError());
    } else {
        var_dump($response->getBody());
    }
}