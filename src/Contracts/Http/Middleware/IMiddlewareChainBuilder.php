<?php declare(strict_types=1);

/**
 * Собирает цепь Middleware
 */

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

interface IMiddlewareChainBuilder
{
    public function createChain(array $middleware): \Closure;
}
