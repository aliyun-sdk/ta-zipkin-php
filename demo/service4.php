<?php

include "common.php";

use AliyunSDK\TaZipkin\Tags\SelfTags;
use AliyunSDK\TaZipkin\Tags\SpanTags;

$tracing = buildTracing(4);

$root = $tracing->getSpanFromMap(
    getallheaders(),
    SpanTags::createAsServer("service4.root"),
    SelfTags::create()->addItem("header", json_encode(getallheaders()))
);

$root->start();

usleep(1000);   // 模拟业务处理

$root->finish();

$tracing->flush();
