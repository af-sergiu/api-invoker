<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

use Psr\Http\Message\ResponseInterface;

interface IResponseReader
{
    public function read(ResponseInterface $response);
}
