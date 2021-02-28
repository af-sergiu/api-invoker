<?php declare(strict_types=1);

/**
 * Собирает цепь Middleware
 */

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

interface IMiddlewareChainBuilder
{
    /**
     * @param array<int, string|\Closure> $middleware
     * @return \Closure
     */
    public function createChain(array $middleware): \Closure;
}
