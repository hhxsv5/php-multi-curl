Multiple cURL in PHP
======

A simple and efficient library wrapping curl_multi_* is used to handle parallel http requests.

## Requirements

* PHP 5.4 or later
* PHP cURL extension

## Installation via Composer([packagist](https://packagist.org/packages/hhxsv5/php-multi-curl))

```bash
composer require "hhxsv5/php-multi-curl:~1.0" -vvv
```

## Usage

```php
//require '../vendor/autoload.php';
use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

$getUrl = 'http://www.weather.com.cn/data/cityinfo/101270101.html';
$postUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=yourtoken';

//Single http request
$options = [//The custom options of cURL
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERAGENT      => 'Multi-cURL client v1.5.0',
];

$c = new Curl(null, $options);
$c->makeGet($getUrl);
$response = $c->exec();
if ($response->hasError()) {
    //Fail
    var_dump($response->getError());
} else {
    //Success
    var_dump($response->getBody());
}

//Reuse $c
$c->makePost($postUrl);
$response = $c->exec();
if ($response->hasError()) {
    //Fail
    var_dump($response->getError());
} else {
    //Success
    var_dump($response->getBody());
}
```
 
```php
//require '../vendor/autoload.php';
use Hhxsv5\PhpMultiCurl\Curl;
use Hhxsv5\PhpMultiCurl\MultiCurl;

$getUrl = 'http://www.weather.com.cn/data/cityinfo/101270101.html';
$postUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=yourtoken';

//Multi http request
$c2 = new Curl();
$c2->makeGet($getUrl);

$c3 = new Curl();
$c3->makePost($postUrl);

$mc = new MultiCurl();

$mc->addCurls([$c2, $c3]);
$allSuccess = $mc->exec();
if ($allSuccess) {
    //All success
    var_dump($c2->getResponse()->getBody(), $c3->getResponse()->getBody());
} else {
    //Some curls failed
    var_dump($c2->getResponse()->getError(), $c3->getResponse()->getError());
}

echo PHP_EOL;

//Reuse $mc
$mc->reset();

$c4 = new Curl();
$c4->makeGet($getUrl);

$c5 = new Curl();
$c5->makePost($postUrl);

$mc->addCurls([$c4, $c5]);
$allSuccess = $mc->exec();
if ($allSuccess) {
    //All success
    var_dump($c4->getResponse()->getBody(), $c5->getResponse()->getBody());
} else {
    //Some curls failed
    var_dump($c4->getResponse()->getError(), $c5->getResponse()->getError());
}
```

```php
//require '../vendor/autoload.php';
use Hhxsv5\PhpMultiCurl\Curl;

//Upload file
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
```

```php
//require '../vendor/autoload.php';
use Hhxsv5\PhpMultiCurl\Curl;

$fileUrl = 'https://avatars2.githubusercontent.com/u/7278743?s=460&v=4';//<?php var_dump($_FILES);
$options = [//The custom options of cURL
    CURLOPT_TIMEOUT        => 3600,//1 hour
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_USERAGENT      => 'Multi-cURL client v1.5.0',
];
$c = new Curl(null, $options);
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
```

## License

[MIT](https://github.com/hhxsv5/php-multi-curl/blob/master/LICENSE)
