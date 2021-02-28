<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http\Middleware;

use Psr\Http\Message\RequestInterface;

interface IBeforeMiddleware
{
    /**
     * Выполняет основной функционал обработки
     */
    public function handle(RequestInterface $request): RequestInterface;
}
