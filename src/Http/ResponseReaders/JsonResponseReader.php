<?php declare(strict_types=1);

/**
 * Читает ответ на запрос с типом application/json
 */

namespace AfSergiu\ApiInvoker\Http\ResponseReaders;

use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
use Psr\Http\Message\ResponseInterface;

class JsonResponseReader implements IResponseReader
{
    public function read(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();
        return json_decode($body, true);
    }
}
