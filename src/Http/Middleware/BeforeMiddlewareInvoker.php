<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Middleware;

use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use Psr\Http\Message\RequestInterface;

class BeforeMiddlewareInvoker extends MiddlewareInvoker implements IBeforeMiddlewareInvoker
{
    public function invokeChain(RequestInterface $request): RequestInterface
    {
        return $this->middlewareChain->__invoke($request);
    }
}
