<?php declare(strict_types=1);

/**
 * Call middleware chain with passed arguments
 */

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

use Psr\Http\Message\RequestInterface;

interface IBeforeMiddlewareInvoker
{
    /**
     * @param array<IBeforeMiddleware|\Closure> $middleware
     * @return mixed
     */
    public function createChain(array $middleware);

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function invokeChain(RequestInterface $request): RequestInterface;
}
