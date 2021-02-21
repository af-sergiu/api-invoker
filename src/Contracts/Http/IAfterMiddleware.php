<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IAfterMiddleware
{
    /**
     * Устанавливает следующий обработчик
     */
    public function setNext(\Closure $next): void;

    /**
     * Выполняет основной функционал обработки
     */
    public function handle(RequestInterface $request, ResponseInterface $response): ResponseInterface;
}
