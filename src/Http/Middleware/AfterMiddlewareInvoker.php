<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Middleware;

use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AfterMiddlewareInvoker extends MiddlewareInvoker implements IAfterMiddlewareInvoker
{
    public function invokeChain(ResponseInterface $response, RequestInterface $request): ResponseInterface
    {
        return $this->middlewareChain->__invoke($response, $request);
    }
}
