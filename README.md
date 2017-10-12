PHP Multi-Curl
======

A simple and efficient library for multi-curl is used to handle parallel requests.

## Requirements

* PHP 5.4 or later
* PHP cURL extension

## Installation via Composer([packagist](https://packagist.org/packages/hhxsv5/php-multi-curl))

```BASH
composer require "hhxsv5/php-multi-curl:~1.0" -vvv
```

## Usage

```PHP
//require '../vendor/autoload.php';
use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

//Single http request
$getUrl = 'http://www.weather.com.cn/data/cityinfo/101270101.html';
$options = [//The custom the curl options
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERAGENT      => 'Multi-Curl Client V1.0',
];
$c1 = new Curl($options);
$c1->makeGet($getUrl);
$ret = $c1->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c1->getResponse());
} else {
    //Fail
    var_dump($c1->getError());
}
```
 
```PHP
//Multi http request
$getUrl = 'http://www.weather.com.cn/data/cityinfo/101270101.html';
$postUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=yourtoken';

$c1 = new Curl();
$c1->makeGet($getUrl);

$c2 = new Curl();
$c2->makePost($postUrl);

$mc = new MultiCurl();

$mc->addCurls([$c1, $c2]);
$ret = $mc->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c1->getResponse(), $c2->getResponse());
} else {
    //Some curls failed
    var_dump($c1->getError(), $c2->getError());
}

//Reuse $mc
$c3 = new Curl();
$c3->makeGet($getUrl);

$c4 = new Curl();
$c4->makePost($postUrl);

$mc->addCurls([$c3, $c4]);
$ret = $mc->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c3->getResponse(), $c4->getResponse());
} else {
    //Some curls failed
    var_dump($c3->getError(), $c4->getError());
}
```

```PHP
//Upload file
$postUrl = 'http://localhost/upload.php';//<?php var_dump($_FILES);
$c = new Curl();
$file1 = new \CURLFile('./olddriver.gif', 'image/gif', 'name1');
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
```

```PHP
//Download file
$fileUrl = 'http://localhost/test.gif';
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
```

## TODO

* HTTP PUT/DELETE
* Anything what you want

## License

[MIT](https://github.com/hhxsv5/php-multi-curl/blob/master/LICENSE)
