<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;

$fileUrl = 'http://localhost/test.gif';//<?php var_dump($_FILES);
$options = [//The custom the curl options
    CURLOPT_TIMEOUT        => 3600,//1 hour
    CURLOPT_CONNECTTIMEOUT => 10,
];
$c = new Curl($options);
$c->makeGet($fileUrl);
$ret = $c->exec();
var_dump($ret);
if ($ret) {
    //Success
    $targetFile = './a/b/c/test.gif';
    var_dump($c->responseToFile($targetFile));
} else {
    //Fail
    var_dump($c->getError());
}