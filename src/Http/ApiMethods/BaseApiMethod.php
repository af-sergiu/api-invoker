<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\ApiMethods;

use AfSergiu\ApiInvoker\Contracts\Http\IApiMethod;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\IArrayStructureBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseApiMethod implements IApiMethod
{
    /**
     * @var string
     */
    protected $httpMethod = 'GET';
    /**
     * @var string
     */
    protected $uri = '';
    /**
     * @var array
     */
    protected $beforeMiddleware = [];
    /**
     * @var array
     */
    protected $afterMiddleware = [];
    /**
     * @var mixed
     */
    private $parameters = '';
    /**
     * @var IRequestConstructor
     */
    private $requestConstructor;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var IRequestInvoker
     */
    private $requestInvoker;
    /**
     * @var IBeforeMiddlewareInvoker
     */
    private $beforeMiddlewareInvoker;
    /**
     * @var IAfterMiddlewareInvoker
     */
    private $afterMiddlewareInvoker;
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(
        IRequestConstructor $requestConstructor,
        IRequestInvoker $requestInvoker,
        IBeforeMiddlewareInvoker $beforeMiddlewareInvoker,
        IAfterMiddlewareInvoker $afterMiddlewareInvoker
    ) {
        $this->requestConstructor = $requestConstructor;
        $this->requestInvoker = $requestInvoker;
        $this->beforeMiddlewareInvoker = $beforeMiddlewareInvoker;
        $this->afterMiddlewareInvoker = $afterMiddlewareInvoker;
    }

    final public function setParameters($parameters)
    {
        if ($parameters instanceof IArrayStructureBuilder) {
            $this->parameters = $parameters->build();
        } else {
            $this->parameters = $parameters;
        }
    }

    /**
     * @param IResponseReader $responseReader
     * @return mixed
     */
    final public function call(IResponseReader $responseReader)
    {
        $this->request = $this->createRequest();
        $this->invokeBeforeMiddleware();
        $this->response = $this->callRequest();
        $this->invokeAfterMiddleware();
        return $this->readRequest($responseReader);
    }

    protected function createRequest(): RequestInterface
    {
        return $this->requestConstructor->create(
            $this->httpMethod,
            $this->uri,
            $this->parameters
        );
    }

    private function invokeBeforeMiddleware(): void
    {
        $this->beforeMiddlewareInvoker->createChain($this->beforeMiddleware);
        $this->beforeMiddlewareInvoker->invokeChain($this->request);
    }

    private function callRequest(): ResponseInterface
    {
        return $this->requestInvoker->invoke($this->request);
    }

    private function invokeAfterMiddleware(): void
    {
        $this->afterMiddlewareInvoker->createChain($this->afterMiddleware);
        $this->afterMiddlewareInvoker->invokeChain($this->response, $this->request);
    }

    protected function getAfterMiddleware(): array
    {
        return [];
    }

    private function readRequest(IResponseReader $responseReader)
    {
        $responseReader->read($this->response);
    }
}
