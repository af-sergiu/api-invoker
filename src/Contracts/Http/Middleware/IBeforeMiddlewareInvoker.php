<?php declare(strict_types=1);

/**
 * Call middleware chain with passed arguments
 */

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

use Psr\Http\Message\RequestInterface;

interface IBeforeMiddlewareInvoker
{
    /**
     * @param array<int, string|\Closure> $middleware
     * @return void
     */
    public function createChain(array $middleware): void;

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function invokeChain(RequestInterface $request): RequestInterface;
}
