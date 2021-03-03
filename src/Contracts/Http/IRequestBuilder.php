<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

use Psr\Http\Message\RequestInterface;

/**
 * Конструирует запрос
 */

interface IRequestBuilder
{
    /**
     * Set http method
     * @param string $httpMethod
     * @return mixed
     */
    public function setMethod(string $httpMethod);

    /**
     * Set uri
     * @param string $uri
     * @return mixed
     */
    public function setUri(string $uri);

    /**
     * Set headers
     * @param array $headers
     * @return mixed
     */
    public function setHeaders(array $headers);

    /**
     * Set body request
     * @param string $parameters
     * @return mixed
     */
    public function setBody(string $parameters);

    /**
     * Get result request
     * @return RequestInterface
     */
    public function getResult(): RequestInterface;
}
