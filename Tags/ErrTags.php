<?php

namespace AliyunSDK\TaZipkin\Tags;

use Zipkin\Span;

/**
 * Class ErrTags
 * @package AliyunSDK\TaZipkin
 */
class ErrTags implements TagsInterface
{
    /**
     * 捕获的异常对象
     *
     * @var \Throwable
     */
    private $exception;

    /**
     * ErrTags constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param \Throwable $exception
     * @return ErrTags
     */
    public static function create(\Throwable $exception): self
    {
        $self = new self();
        $self->exception = $exception;
        return $self;
    }

    /**
     * @inheritDoc
     * @param Span $span
     */
    public function applyTo(Span $span): void
    {
        $span->tag("event", "error");
        $span->tag("error.kind", get_class($this->exception));
        $span->tag("message", $this->exception->getMessage());
        $span->tag("stack", $this->exception->getTraceAsString());
    }
}
