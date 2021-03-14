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
    /**
     * @var string
     */
    protected $urlEncodedParameters='';

    final public function setMethod(string $httpMethod)
    {
        $this->httpMethod = $httpMethod;
    }

    final public function setUri(string $uri)
    {
        $this->uri = $uri;
    }

    final public function setHeaders(array $addHeaders)
    {
        $this->headers = array_merge($this->getRequireHeaders(), $addHeaders);
    }

    /**
     * Возвращает обязательные заголовки для запросов этого типа api
     */
    abstract protected function getRequireHeaders(): array;

    final public function setBodyParameters(array $parameters)
    {
        $this->body = $this->prepareBodyParameters($parameters);
    }

    /**
     * Возвращает преобразованный в строку массив с параметрами в виде, характерном для данного типа API (json, xml,
     * application/x-www-form-urlencoded и т.п.)
     */
    abstract protected function prepareBodyParameters(array $parameters): string;

    public function setUriParameters(array $parameters)
    {
        $this->urlEncodedParameters = http_build_query($parameters);
    }

    final public function setBody(string $parameters)
    {
        $this->body = $parameters;
    }

    public function getResult(): RequestInterface
    {
        return new Request($this->httpMethod, $this->constructUri(), $this->headers, $this->body);
    }

    private function constructUri(): string
    {
        if ($this->urlEncodedParameters) {
            return "{$this->uri}?{$this->urlEncodedParameters}";
        } else {
            return $this->uri;
        }
    }
}
