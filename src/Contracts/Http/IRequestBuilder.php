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
     */
    public function setMethod(string $httpMethod): void;

    /**
     * Set uri
     */
    public function setUri(string $uri): void;

    /**
     * Set headers
     */
    public function setHeaders(array $addHeaders): void;

    /**
     * Set body request
     */
    public function setBody(string $parameters): void;

    /**
     * Set converted to string array parameters in api defined format (json, xml etc) to body
     */
    public function setBodyParameters(array $parameters): void;

    /**
     * Set converted to string array parameters for GET requests
     */
    public function setUriParameters(array $parameters): void;

    /**
     * Get result request
     */
    public function getResult(): RequestInterface;
}
