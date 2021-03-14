<?php declare(strict_types=1);

/**
 * Example of concrete API method
 */

namespace AfSergiu\ApiInvoker\Http\ApiMethods;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Constructors\RequestConstructor;

class DemoBaseApiMethod extends BaseApiMethod
{
    /**
     * @var string
     */
    protected $httpMethod = 'GET';
    /**
     * @var string
     */
    protected $uri = 'https://httpbin.org/get';
}
