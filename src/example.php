<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

$c1Ret = null;
$c1 = new Curl();
$c1->makeGet('https://passport.medlinker.com/sections');

$c2Ret = null;
$c2 = new Curl();
$c2->makeGet('https://passport.medlinker.com/titles');

$mc = new MultiCurl();
$mc->addCurls([$c1, $c2]);

$result = [];
$ret = $mc->exec($result);

var_dump($result);