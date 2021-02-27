<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

interface IMiddleware
{
    /**
     * Устанавливает следующий обработчик
     */
    public function setNext(IMiddleware $next): void;
}
