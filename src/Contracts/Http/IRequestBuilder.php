<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

use Psr\Http\Message\RequestInterface;

/**
 * Конструирует запрос
 */

interface IRequestBuilder
{
    public function setMethod(string $httpMethod);

    public function setUrl(string $uri);

    public function setHeaders(array $headers);

    public function setParameters(array $parameters);

    public function getResult(): RequestInterface;
}
