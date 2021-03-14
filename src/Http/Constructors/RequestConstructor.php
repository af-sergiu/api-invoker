<?php declare(strict_types=1);

/**
 * Класс, конструирующий простой запрос
 */

namespace AfSergiu\ApiInvoker\Http\Constructors;

use AfSergiu\ApiInvoker\Http\Builders\BaseRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use Psr\Http\Message\RequestInterface;

final class RequestConstructor implements IRequestConstructor
{
    /**
     * @var BaseRequestBuilder
     */
    private $builder;

    public function setBuilder(IRequestBuilder $builder): void
    {
        $this->builder = $builder;
    }

    public function create(string $method = 'GET', string $uri, $parameters, array $addHeaders=[]): RequestInterface
    {
        $this->builder->setMethod($method);
        $this->builder->setUri($uri);
        $this->builder->setHeaders($addHeaders);
        $this->resolveAddParametersStrategy($method, $parameters);
        return $this->builder->getResult();
    }

    private function resolveAddParametersStrategy(string $method, $parameters): void
    {
        if ($method === 'GET' && is_array($parameters)) {
            $this->setParametersForGetMethod($parameters);
        } else if ($method !== 'GET' && is_array($parameters)) {
            $this->setParametersBody($parameters);
        } else if ($method !== 'GET' && is_string($parameters)) {
            $this->setParametersBody($parameters);
        }
    }

    private function setParametersForGetMethod($parameters): void
    {
        if (is_array($parameters)) {
            $this->builder->setUriParameters($parameters);
        } else {
            throw new \InvalidArgumentException("For get request parameters must have array type");
        }
    }

    private function setParametersBody($parameters): void
    {
        if (is_array($parameters)) {
            $this->builder->setBodyParameters($parameters);
        } else if (is_string($parameters)) {
            $this->builder->setBody($parameters);
        } else {
            throw new \InvalidArgumentException("Parameters must have array or string type");
        }
    }
}
