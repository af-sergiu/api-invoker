<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IAfterMiddleware extends IMiddleware
{
    /**
     * Выполняет основной функционал обработки
     */
    public function handle(RequestInterface $request, ResponseInterface $response): ResponseInterface;
}
