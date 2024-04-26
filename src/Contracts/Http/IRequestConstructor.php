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
     * Устанавливает билдер, который будет конструировать запрос
     */
    public function setBuilder(IRequestBuilder $builder): void;

    /**
     * Конструирует запрос с помощью билдера по умолчанию, чтобы не создавать большое кол-во классов для простых запросов
     * @param array|string $parameters
     */
    public function create(string $method, string $uri, mixed $parameters, array $addHeaders=[]): RequestInterface;
}
