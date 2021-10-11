<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IAfterMiddleware
{
    /**
     * Выполняет основной функционал обработки
     * @param ResponseInterface $response
     * @param RequestInterface $request
     * @param \Closure $next
     * @return ResponseInterface
     */
    public function handle(ResponseInterface $response, RequestInterface $request, \Closure $next): ResponseInterface;
}
