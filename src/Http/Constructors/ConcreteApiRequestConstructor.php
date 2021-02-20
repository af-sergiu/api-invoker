<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Constructors;

use AfSergiu\ApiInvoker\Http\Builders\JsonRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use Psr\Http\Message\RequestInterface;

/**
 * Класс, конструирующий простой запрос
 */

class ConcreteApiRequestConstructor implements IRequestConstructor
{
    /**
     * @var JsonRequestBuilder
     */
    private $defaultBuilder;

    public function __construct(JsonRequestBuilder $defaultBuilder)
    {
        $this->defaultBuilder = $defaultBuilder;
    }

    public function create(IRequestBuilder $requestBuilder): RequestInterface
    {
        $requestBuilder->setHeaders([
            'Content-Type' => 'text/json'
        ]);
        return $requestBuilder->getResult();
    }

    public function createArrayRequest(string $method, string $url, array $parameters): RequestInterface
    {
        $this->defaultBuilder->setMethod($method);
        $this->defaultBuilder->setUrl($url);
        $this->defaultBuilder->setParameters($parameters);
        $this->defaultBuilder->setHeaders([
            'Content-Type' => 'text/json'
        ]);
        return $this->defaultBuilder->getResult();
    }
}
