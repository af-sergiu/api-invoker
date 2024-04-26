<?php declare(strict_types=1);

/**
 * Example of concrete API method
 */

namespace AfSergiu\ApiInvoker\Http\Methods\Json;

use AfSergiu\ApiInvoker\Http\Methods\BaseMethod;

class DemoMethod extends BaseMethod
{
    /**
     * @var string
     */
    protected string $httpMethod = 'GET';
    /**
     * @var string
     */
    protected string $uri = 'https://httpbin.org/get';
}
