<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Builders;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

abstract class BaseRequestBuilder implements IRequestBuilder
{
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var string
     */
    protected $httpMethod;
    /**
     * @var string
     */
    protected $uri;
    /**
     * @var array
     */
    protected $headers;
    /**
     * @var string
     */
    protected $body;

    public function getResult(): RequestInterface
    {
        return new Request($this->httpMethod, $this->uri, $this->headers, $this->body);
    }

    public function setMethod(string $httpMethod)
    {
        $this->httpMethod = $httpMethod;
    }

    public function setUri(string $uri)
    {
        $this->uri = $uri;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }
}
