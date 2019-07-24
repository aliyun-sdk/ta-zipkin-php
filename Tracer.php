<?php

namespace AliyunSDK\TaZipkin;

use AliyunSDK\TaZipkin\Tags\TagsInterface;
use Zipkin\Propagation\SamplingFlags;
use Zipkin\Span;
use Zipkin\Tracer as ZipkinTracer;

/**
 * Class Tracer
 * @package AliyunSDK\TaZipkin
 */
class Tracer
{
    /**
     * 官方库Tracer
     *
     * @var ZipkinTracer
     */
    private $zipkinTracer;

    /**
     * Tracer constructor.
     * @param ZipkinTracer $tracer
     */
    public function __construct(ZipkinTracer $tracer)
    {
        $this->zipkinTracer = $tracer;
    }

    /**
     * @param SamplingFlags $flags
     * @param TagsInterface ...$tags
     * @return Span
     */
    public function newTrace(SamplingFlags $flags, TagsInterface ...$tags): Span
    {
        $span = $this->zipkinTracer->newTrace($flags);

        foreach ($tags as $tag)
        {
            $tag->applyTo($span);
        }

        return $span;
    }

    /**
     * @param SamplingFlags $flags
     * @param TagsInterface ...$tags
     * @return Span
     */
    public function nextSpan(SamplingFlags $flags, TagsInterface ...$tags): Span
    {
        $span = $this->zipkinTracer->nextSpan($flags);

        foreach ($tags as $tag)
        {
            $tag->applyTo($span);
        }

        return $span;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->zipkinTracer, $name], $arguments);
    }
}