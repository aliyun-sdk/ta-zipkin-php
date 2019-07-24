<?php

use Zipkin\Span;
use AliyunSDK\TaZipkin\Tags\ErrTags;

/**
 * @param Span $span
 * @param callable $actionMethod
 * @return mixed
 * @throws Throwable
 */
function simpleWrapper(Span $span, callable $actionMethod)
{
    $span->start();

    try
    {
        return $actionMethod($span);
    }
    catch (\Throwable $ex)
    {
        ErrTags::create($ex)->applyTo($span);
        throw $ex;
    }
    finally
    {
        $span->finish();
    }
}