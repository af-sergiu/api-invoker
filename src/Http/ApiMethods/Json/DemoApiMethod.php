<?php declare(strict_types=1);

/**
 * Example of concrete API method
 */

namespace AfSergiu\ApiInvoker\Http\ApiMethods;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Constructors\JsonApiRequestConstructor;

class DemoApiMethod extends ApiMethod
{
    /**
     * @var string
     */
    protected $httpMethod = 'GET';
    /**
     * @var string
     */
    protected $uri = 'https://httpbin.org/get';
    /**
     * @var array
     */
    protected $parameters = [];
    /**
     * @var JsonApiRequestConstructor
     */
    protected $requestConstructor;

    public function __construct(
        JsonApiRequestConstructor $requestConstructor,
        IRequestInvoker $requestInvoker,
        IBeforeMiddlewareInvoker $beforeMiddlewareInvoker,
        IAfterMiddlewareInvoker $afterMiddlewareInvoker
    ) {
        parent::__construct($requestConstructor, $requestInvoker, $beforeMiddlewareInvoker, $afterMiddlewareInvoker);
    }

    /**
     * @param array $parameters
     * @return mixed|void
     */
    public function setRequestParameters(array $parameters)
    {
        /** @var \DateTime $datetime */
        $datetime = $parameters['datetime'];
        $this->parameters['date'] = $datetime->format('D.M.Y');
    }
}
