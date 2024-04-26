<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IAfterMiddleware
{
    /**
     * Выполняет основной функционал обработки
     */
    public function handle(ResponseInterface $response, RequestInterface $request, \Closure $next): ResponseInterface;
}
