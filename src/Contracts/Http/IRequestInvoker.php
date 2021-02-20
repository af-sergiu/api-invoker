<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;


use Psr\Http\Message\RequestInterface;

interface IRequestInvoker
{
    public function invoke(RequestInterface $request);

    public function read(IResponseReader $reader);
}
