<?php

namespace AliyunSDK\TaZipkin\Tags;

use Zipkin\Span;

/**
 * Class SelfTags
 * @package AliyunSDK\TaZipkin\Tags
 */
class SelfTags implements TagsInterface
{
    /**
     * @var array
     */
    private $items;

    /**
     * SelfTags constructor.
     * @param array $items
     */
    private function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param array $items
     * @return SelfTags
     */
    public static function create(array $items = []): self
    {
        return new self($items);
    }

    /**
     * @param string $key
     * @param $value
     * @return SelfTags
     */
    public function addItem(string $key, $value): self
    {
        $this->items[$key] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     * @param Span $span
     */
    public function applyTo(Span $span): void
    {
        foreach ($this->items as $key => $value)
        {
            $span->tag($key, $value);
        }
    }
}