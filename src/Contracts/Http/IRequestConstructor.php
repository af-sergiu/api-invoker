<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

use Psr\Http\Message\RequestInterface;

/**
 * Управляет конструированием запроса (Director). Вся логика по установке специфичных параметров запроса конкретных API
 * - заголовки, токены, авторизация и тп реализуется здесь
 */

interface IRequestConstructor
{
    /**
     * Управляет строительством запроса
     * @param IRequestBuilder $requestBuilder
     * @return RequestInterface
     */
    public function create(IRequestBuilder $requestBuilder): RequestInterface;

    /**
     * Конструирует запрос с помощью билдера по умолчанию, чтобы не создавать большое кол-во классов для простых запросов
     * @param string $uri
     * @param array $parameters
     * @param string $method
     * @return RequestInterface
     */
    public function createByDefaultBuilder(string $uri, array $parameters=[], string $method='GET'): RequestInterface;
}
