<?php
require '../vendor/autoload.php';
use Hhxsv5\PhpMultiCurl\Curl;

$fileUrl = 'https://avatars2.githubusercontent.com/u/7278743?s=460&v=4';//<?php var_dump($_FILES);
$options = [//The custom options of cURL
    CURLOPT_TIMEOUT        => 3600,//1 hour
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_USERAGENT      => 'Multi-cURL client v1.5.0',
];
$c = new Curl($options);
$c->makeGet($fileUrl);
$response = $c->exec();
if ($response->hasError()) {
    //Fail
    var_dump($response->getError());
} else {
    //Success
    $targetFile = './a/b/c/test.png';
    var_dump($c->responseToFile($targetFile));
}