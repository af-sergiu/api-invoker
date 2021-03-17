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
    public function setHeaders(array $addHeaders);

    /**
     * Set body request
     * @param string $parameters
     * @return mixed
     */
    public function setBody(string $parameters);

    /**
     * Set converted to string array parameters in api defined format (json, xml etc) to body
     * @param array $parameters
     * @return mixed
     */
    public function setBodyParameters(array $parameters);

    /**
     * Set converted to string array parameters for GET requests
     * @param array $parameters
     * @return mixed
     */
    public function setUriParameters(array $parameters);

    /**
     * Get result request
     * @return RequestInterface
     */
    public function getResult(): RequestInterface;
}
