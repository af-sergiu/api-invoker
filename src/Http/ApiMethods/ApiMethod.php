<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\ApiMethods;

use AfSergiu\ApiInvoker\Contracts\Http\IApiMethod;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;

class ApiMethod implements IApiMethod
{
    /**
     * @var IRequestConstructor
     */
    private $requestConstructor;
    /**
     * @var IRequestInvoker
     */
    private $requestInvoker;
    /**
     * @var string
     */
    protected $httpMethod = '';
    /**
     * @var string
     */
    protected $uri = '';
    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(IRequestConstructor $requestConstructor, IRequestInvoker $requestInvoker)
    {

        $this->requestConstructor = $requestConstructor;
        $this->requestInvoker = $requestInvoker;
    }

    public function call(IResponseReader $responseReader)
    {
        // TODO: Implement call() method.
    }

    protected function getBeforeMiddlewares(): array
    {
        return [];
    }

    protected function getAfterMiddlewares(): array
    {
        return [];
    }

    public function changeRequestBuilder(IRequestBuilder $requestBuilder)
    {
        // TODO: Implement changeRequestBuilder() method.
    }

    public function changeRequestArrayBuilder(array $requestBuilder)
    {
        // TODO: Implement changeRequestArrayBuilder() method.
    }
}
