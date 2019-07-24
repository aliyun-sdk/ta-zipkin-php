<?php

namespace AliyunSDK\TaZipkin\Tags;

use Zipkin\Span;

/**
 * Class HttpTags
 * @package AliyunSDK\TaZipkin
 */
class HttpTags implements TagsInterface
{
    /**
     * 请求地址
     *
     * @var string
     */
    private $url;

    /**
     * 请求方式
     *
     * @var string
     */
    private $method;

    /**
     * 响应状态码
     *
     * @var integer
     */
    private $statusCode;

    /**
     * HttpTags constructor.
     * @param string $url
     * @param string $method
     */
    private function __construct(string $url, string $method)
    {
        $this->url = $url;
        $this->method = $method;
    }

    /**
     * @param string $url
     * @param string $method
     * @return HttpTags
     */
    public static function create(string $url, string $method = "GET"): self
    {
        return new self($url, $method);
    }

    /**
     * @param int $code
     * @return HttpTags
     */
    public function withStatusCode(int $code): self
    {
        $self = clone  $this;
        $self->statusCode = $code;
        return $self;
    }

    /**
     * @inheritDoc
     * @param Span $span
     */
    public function applyTo(Span $span): void
    {
        $span->tag("http.url", $this->url);
        $span->tag("http.method", $this->method);
        $span->tag("http.status_code", $this->statusCode);
    }
}