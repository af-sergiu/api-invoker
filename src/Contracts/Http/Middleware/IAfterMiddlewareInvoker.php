<?php declare(strict_types=1);

/**
 * Call middleware chain with passed arguments
 */

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IAfterMiddlewareInvoker
{
    /**
     * @param array<IAfterMiddleware|\Closure> $middleware
     * @return mixed
     */
    public function createChain(array $middleware);

    /**
     * @param ResponseInterface $response
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function invokeChain(ResponseInterface $response, RequestInterface $request): ResponseInterface;

}
