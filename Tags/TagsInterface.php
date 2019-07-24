<?php

namespace AliyunSDK\TaZipkin\Tags;

use Zipkin\Span;

/**
 * Interface TagsInterface
 * @package AliyunSDK\TaZipkin
 */
interface TagsInterface
{
    /**
     * 将标签应用于给定Span
     *
     * @param Span $span
     */
    public function applyTo(Span $span): void;
}
