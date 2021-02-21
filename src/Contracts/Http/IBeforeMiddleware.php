<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

use Psr\Http\Message\RequestInterface;

interface IBeforeMiddleware
{
    /**
     * Устанавливает следующий обработчик
     */
    public function setNext(\Closure $next): void;

    /**
     * Выполняет основной функционал обработки
     */
    public function handle(RequestInterface $request): RequestInterface;
}
