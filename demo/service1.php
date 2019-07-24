<?php

include "common.php";

use AliyunSDK\TaZipkin\Tags\DBTags;
use AliyunSDK\TaZipkin\Tags\HttpTags;
use AliyunSDK\TaZipkin\Tags\SpanTags;
use AliyunSDK\TaZipkin\Tags\SelfTags;
use Zipkin\Span;

$tracing = buildTracing(1);

$root = $tracing->getSpanFromMap(
    getallheaders(),
    SpanTags::createAsServer("service1.root"),
    SelfTags::create()->addItem("header", json_encode(getallheaders()))
);

$root->start();

{
    $span2 = $tracing->nextSpan(
        $root->getContext(),
        SpanTags::createAsClient("service1.sql.query"),
        DBTags::create()->withStatement("SET AGE = 18")
    );

    simpleWrapper($span2, function ()
    {
        usleep(1000);   // 模拟SQL查询
    });
}

{
    $span3 = $tracing->nextSpan(
        $root->getContext(),
        SpanTags::createAsClient("service1.http.get.service2")
    );

    simpleWrapper($span3, function (Span $span3) use ($tracing)
    {
        $headers = [];
        $url = "http://localhost:8082/service2.php";
        $tracing->setSpanIntoMap($headers, $span3);
        httpGet($url, $headers);
        HttpTags::create($url)->withStatusCode(200)->applyTo($span3);
    });
}

{
    try
    {
        $span4 = $tracing->nextSpan(
            $root->getContext(),
            SpanTags::createAsClient("service1.http.get.service3")
        );

        simpleWrapper($span4, function (Span $span4) use ($tracing)
        {
            $headers = [];
            $url = "http://localhost:8083/service3.php";
            $tracing->setSpanIntoMap($headers, $span4);
            httpGet($url, $headers);
            HttpTags::create($url)->withStatusCode(200)->applyTo($span4);

            throw new InvalidArgumentException("my test err message");
        });
    }
    catch (\Throwable $ex)
    {
    }
}

$root->finish();

$tracing->flush();