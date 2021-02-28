<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

use Psr\Http\Message\RequestInterface;

interface IBeforeMiddleware
{
    /**
     * Выполняет основной функционал обработки
     * @param RequestInterface $request
     * @param \Closure $next
     * @return RequestInterface
     */
    public function handle(RequestInterface $request, \Closure $next): RequestInterface;
}
