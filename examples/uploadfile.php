<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;

$postUrl = 'http://localhost/upload.php';//<?php var_dump($_FILES);

$options = [//The custom the curl options
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERAGENT      => 'Multi-Curl Client V1.0',
];
$c = new Curl($options);

$file1 = new CURLFile('./olddriver.gif', 'image/gif', 'name1');
$params = ['file1' => $file1];
$c->makePost($postUrl, $params);
$ret = $c->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c->getResponse());
} else {
    //Fail
    var_dump($c->getError());
}