<?php

namespace AliyunSDK\TaZipkin\Tags;

use Zipkin\Span;

/**
 * Class DBTags
 * @package AliyunSDK\TaZipkin
 */
class DBTags implements TagsInterface
{
    /**
     * 类型, 如sql, redis, hbase
     *
     * @var string
     */
    private $type = "sql";

    /**
     * 数据库名称
     *
     * @var string
     */
    private $instance;

    /**
     * 访问数据库用户
     *
     * @var string
     */
    private $user;

    /**
     * 相应的类型的数据库语句
     *
     * @var string
     */
    private $statement;

    /**
     * DBTags constructor.
     * @param array $config
     */
    private function __construct(array $config = [])
    {
        foreach ($config as $key => $value)
        {
            $this->{$key} = $value;
        }
    }

    /**
     * @param array $config
     * @return DBTags
     */
    public static function create(array $config = []): self
    {
        return new self($config);
    }

    /**
     * @param string $statement
     * @return DBTags
     */
    public function withStatement(string $statement): self
    {
        $self = clone $this;
        $self->statement = $statement;
        return $self;
    }

    /**
     * @inheritDoc
     * @param Span $span
     */
    public function applyTo(Span $span): void
    {
        $span->tag("db.type", $this->type);
        $span->tag("db.statement", $this->statement);

        !is_null($this->user) && $span->tag("db.user", $this->user);
        !is_null($this->instance) && $span->tag("db.instance", $this->instance);
    }
}
