<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts;

interface IMiddlewareDirector
{
    public function invoke(array $middlewares, \Closure $lastCallable): void;
}
