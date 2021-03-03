<?php declare(strict_types=1);

/**
 * Класс, конструирующий простой запрос
 */

namespace AfSergiu\ApiInvoker\Http\Constructors;

use AfSergiu\ApiInvoker\Http\Builders\BaseRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use Psr\Http\Message\RequestInterface;

class JsonApiRequestConstructor implements IRequestConstructor
{
    /**
     * @var BaseRequestBuilder
     */
    private $defaultBuilder;

    public function __construct(BaseRequestBuilder $defaultBuilder)
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

    public function createByDefaultBuilder(string $uri, array $parameters = [], string $method = 'GET'): RequestInterface
    {
        $this->defaultBuilder->setMethod($method);
        $this->defaultBuilder->setUri($uri);
        $this->defaultBuilder->setBody($this->prepareParametersForBody($parameters));
        $this->defaultBuilder->setHeaders([
            'Content-Type' => 'text/json'
        ]);
        return $this->defaultBuilder->getResult();
    }

    private function prepareParametersForBody(array $parameters): string
    {
        return json_encode($parameters);
    }
}
