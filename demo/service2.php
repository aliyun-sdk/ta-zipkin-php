<?php

include "common.php";

use AliyunSDK\TaZipkin\Tags\HttpTags;
use AliyunSDK\TaZipkin\Tags\SelfTags;
use AliyunSDK\TaZipkin\Tags\SpanTags;

$tracing = buildTracing(2);

$root = $tracing->getSpanFromMap(
    getallheaders(),
    SpanTags::createAsServer("service2.root"),
    SelfTags::create()->addItem("header", json_encode(getallheaders()))
);

$root->start();

usleep(1000);   // 模拟业务处理

$headers = [];
$tracing->setSpanIntoMap($headers, $root);
$url = "http://localhost:8084/service4.php";
httpGet($url, $headers);
HttpTags::create($url)->withStatusCode(200)->applyTo($root);

usleep(1000);   // 模拟业务处理

$root->finish();

$tracing->flush();