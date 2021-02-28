<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IAfterMiddleware
{
    /**
     * Выполняет основной функционал обработки
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param \Closure $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, ResponseInterface $response, \Closure $next): ResponseInterface;
}
