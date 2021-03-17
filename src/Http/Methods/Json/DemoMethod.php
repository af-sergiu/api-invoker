<?php declare(strict_types=1);

/**
 * Example of concrete API method
 */

namespace AfSergiu\ApiInvoker\Http\Methods;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Constructors\RequestConstructor;

class DemoMethod extends BaseMethod
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
