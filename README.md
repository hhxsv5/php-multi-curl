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
$ret = $c1->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c1->getResponse());
} else {
    //Fail
    var_dump($c1->getError());
}


//Multi http request
$c2 = new Curl();
$c2->makeGet($getUrl);

$c3 = new Curl();
$c3->makePost($postUrl);

$mc = new MultiCurl();

$mc->addCurls([$c2, $c3]);
$ret = $mc->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c2->getResponse(), $c3->getResponse());
} else {
    //Some curls failed
    var_dump($c2->getError(), $c3->getError());
}

//Reuse $mc
$c4 = new Curl();
$c4->makeGet($getUrl);

$c5 = new Curl();
$c5->makePost($postUrl);

$mc->addCurls([$c4, $c5]);
$ret = $mc->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c4->getResponse(), $c5->getResponse());
} else {
    //Some curls failed
    var_dump($c4->getError(), $c5->getError());
}

//upload file
$postUrl = 'http://localhost/upload.php';//<?php var_dump($_FILES);
$options = [//The custom the curl options
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERAGENT      => 'Multi-Curl Client V1.0',
];
$c6 = new Curl($options);
$file1 = new \CURLFile('./olddriver.gif', 'image/gif', 'name1');
$params = ['file1' => $file1];
$c6->makePost($postUrl, $params);
$ret = $c6->exec();
var_dump($ret);
if ($ret) {
    //Success
    var_dump($c6->getResponse());
} else {
    //Fail
    var_dump($c6->getError());
}
```

## TODO

* HTTP PUT/DELETE
* Download file
* Anything what you want

## License

[MIT](https://github.com/hhxsv5/php-multi-curl/blob/master/LICENSE)
