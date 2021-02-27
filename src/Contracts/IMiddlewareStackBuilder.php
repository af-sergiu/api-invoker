<?php declare(strict_types=1);

/**
 * Собирает цепь Middleware
 */

namespace AfSergiu\ApiInvoker\Contracts;

interface IMiddlewareStackBuilder
{
    public function createChain(array $middleware): \Closure;
}
