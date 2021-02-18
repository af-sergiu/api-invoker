<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Builders;

use AfSergiu\ApiInvoker\Contracts\IRequestBuilder;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

abstract class BaseRequestBuilder implements IRequestBuilder
{
    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct()
    {
        $this->request = new Request('POST', '', []);
    }

    public function setMethod(string $httpMethod)
    {
        //$this->request->
    }
}