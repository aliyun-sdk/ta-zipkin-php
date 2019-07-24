<?php

namespace AliyunSDK\TaZipkin;

use Zipkin\TracingBuilder;

/**
 * Class Builder
 * @package AliyunSDK\TaZipkin
 */
class Builder extends TracingBuilder
{
    /**
     * @inheritDoc
     * @return Builder
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @return Tracing|Tracer|\Zipkin\Tracer
     */
    public function build()
    {
        return new Tracing(parent::build());
    }
}