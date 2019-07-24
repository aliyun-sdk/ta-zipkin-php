<?php

namespace AliyunSDK\TaZipkin\Tags;

use Zipkin\Endpoint;
use const Zipkin\Kind\CLIENT;
use const Zipkin\Kind\CONSUMER;
use const Zipkin\Kind\PRODUCER;
use const Zipkin\Kind\SERVER;
use Zipkin\Span;

/**
 * Class SpanTags
 * @package AliyunSDK\TaZipkin\Tags
 */
class SpanTags implements TagsInterface
{
    /**
     * Span 名称
     *
     * @var string
     */
    private $name;

    /**
     * Span 类型
     *
     * @var string
     */
    private $kind;

    /**
     * 对端
     *
     * @var Endpoint
     */
    private $remoteEndpoint;

    /**
     * SpanTags constructor.
     * @param string $name
     * @param string $kind
     */
    private function __construct(string $name, string $kind)
    {
        $this->name = $name;
        $this->kind = $kind;
    }

    /**
     * @param string $name
     * @return SpanTags
     */
    public static function createAsProducer(string $name): self
    {
        return new self($name, PRODUCER);
    }

    /**
     * @param string $name
     * @return SpanTags
     */
    public static function createAsConsumer(string $name): self
    {
        return new self($name, CONSUMER);
    }

    /**
     * @param string $name
     * @return SpanTags
     */
    public static function createAsServer(string $name): self
    {
        return new self($name, SERVER);
    }

    /**
     * @param string $name
     * @return SpanTags
     */
    public static function createAsClient(string $name): self
    {
        return new self($name, CLIENT);
    }

    /**
     * @param Endpoint $endpoint
     * @return SpanTags
     */
    public function setRemoteEndpoint(Endpoint $endpoint): self
    {
        $this->remoteEndpoint = $endpoint;
        return $this;
    }

    /**
     * @param Span $span
     */
    public function applyTo(Span $span): void
    {
        $span->setName($this->name);
        $span->setKind($this->kind);
        !is_null($this->remoteEndpoint)
        && $span->setRemoteEndpoint($this->remoteEndpoint);
    }
}