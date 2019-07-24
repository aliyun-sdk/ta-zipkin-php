<?php

use AliyunSDK\TaZipkin\Builder;
use Zipkin\Endpoint;
use Zipkin\Reporters\Http;
use Zipkin\Samplers\BinarySampler;

include "../vendor/autoload.php";

function buildTracing(int $n)
{
    $reportURL = "您的上报地址";

    return Builder::create()
        ->havingLocalEndpoint(Endpoint::create("service.{$n}", "10.10.10.1{$n}"))
        ->havingTraceId128bits(true)
        ->havingReporter(new Http(Http\CurlFactory::create(), ["endpoint_url" => $reportURL]))
        ->havingSampler(BinarySampler::createAsAlwaysSample())
        ->build();
}

function httpGet(string $url, array $header): string
{
    $lines = [];

    foreach ($header as $key => $value)
    {
        $lines[] = "{$key}:{$value}";
    }

    $context = stream_context_create([
        "http" => [
            "method" => "GET",
            "header" => $lines,
        ],
    ]);

    return file_get_contents($url, false, $context);
}