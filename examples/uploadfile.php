<?php
require '../vendor/autoload.php';

use Hhxsv5\PhpMultiCurl\Curl;

$postUrl = 'http://localhost/upload.php';//<?php var_dump($_FILES);

$options = [//The custom options of cURL
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERAGENT      => 'Multi-cURL client v1.5.0',
];
$c = new Curl(null, $options);

$file1 = new CURLFile('./olddriver.gif', 'image/gif', 'name1');
$params = ['file1' => $file1];
$c->makePost($postUrl, $params);
$response = $c->exec();
if ($response->hasError()) {
    //Fail
    var_dump($response->getError());
} else {
    //Success
    var_dump($response->getBody());
}