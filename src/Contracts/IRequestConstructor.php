<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts;

use Psr\Http\Message\RequestInterface;

/**
 * Управляет конструированием запроса (Director). Вся логика по установке специфичных параметров запроса - заголовки,
 * авторизация и тп реализуется здесь
 */

interface IRequestConstructor
{
    /**
     * Управляет строительством запроса
     * @param IRequestBuilder $requestBuilder
     * @return RequestInterface
     */
    public function create(IRequestBuilder $requestBuilder): RequestInterface;
}