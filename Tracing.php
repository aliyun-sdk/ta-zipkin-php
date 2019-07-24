<?php

namespace AliyunSDK\TaZipkin;

use AliyunSDK\TaZipkin\Tags\TagsInterface;
use Psr\Http\Message\RequestInterface;
use Zipkin\Propagation\Map;
use Zipkin\Propagation\Propagation;
use Zipkin\Propagation\RequestHeaders;
use Zipkin\Span;
use Zipkin\Tracing as ZipkinTracing;

/**
 * Class Tracing
 * @package AliyunSDK\TaZipkin
 */
class Tracing implements ZipkinTracing
{
    /**
     * 官方库Tracing
     *
     * @var ZipkinTracing
     */
    private $zipkinTracing;

    /**
     * Tracing constructor.
     * @param ZipkinTracing $tracing
     */
    public function __construct(ZipkinTracing $tracing)
    {
        $this->zipkinTracing = $tracing;
    }

    /**
     * @var Tracer
     * @see getTracer
     */
    private $tracer;

    /**
     * @return Tracer|\Zipkin\Tracer
     */
    public function getTracer()
    {
        if (is_null($this->tracer))
        {
            $this->tracer = new Tracer($this->zipkinTracing->getTracer());
        }

        return $this->tracer;
    }

    /**
     * @return Propagation
     */
    public function getPropagation()
    {
        return $this->zipkinTracing->getPropagation();
    }

    /**
     * @return bool
     */
    public function isNoop()
    {
        return $this->zipkinTracing->isNoop();
    }

    /**
     * 从Request请求生成Span
     *
     * @param RequestInterface $request
     * @param TagsInterface ...$tags
     * @return Span
     */
    public function getSpanFromRequest(RequestInterface $request, TagsInterface ...$tags): Span
    {
        $context = $this->getPropagation()->getExtractor(new RequestHeaders())($request);
        return $this->getTracer()->nextSpan($context, ...$tags);
    }

    /**
     * 将Span写入Request请求
     *
     * @param RequestInterface $request
     * @param Span|null $span
     */
    public function setSpanIntoRequest(RequestInterface $request, Span $span)
    {
        $this->getPropagation()->getInjector(new RequestHeaders())($span->getContext(), $request);
    }

    /**
     * 从Map生成Span
     *
     * @param array|\ArrayAccess $mapData
     * @param TagsInterface ...$tags
     * @return Span
     */
    public function getSpanFromMap($mapData, TagsInterface ...$tags): Span
    {
        $context = $this->getPropagation()->getExtractor(new Map())($mapData);
        return $this->getTracer()->nextSpan($context, ...$tags);
    }

    /**
     * 将Span写入Map
     *
     * @param array|\ArrayAccess $mapData
     * @param Span|null $span
     */
    public function setSpanIntoMap(&$mapData, Span $span)
    {
        $this->getPropagation()->getInjector(new Map())($span->getContext(), $mapData);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getTracer(), $name], $arguments);
    }
}