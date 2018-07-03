<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;

$fileUrl = 'http://localhost/test.gif';//<?php var_dump($_FILES);
$options = [//The custom the curl options
    CURLOPT_TIMEOUT        => 3600,//1 hour
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_USERAGENT      => 'Multi-Curl Client v1.5.0',
];
$c = new Curl($options);
$c->makeGet($fileUrl);
$response = $c->exec();
if ($response->hasError()) {
    //Fail
    var_dump($response->getError());
} else {
    //Success
    $targetFile = './a/b/c/test.gif';
    var_dump($c->responseToFile($targetFile));
}