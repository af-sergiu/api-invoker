<?php declare(strict_types=1);

/**
 * Читает ответ на запрос с типом application/json
 */

namespace AfSergiu\ApiInvoker\Http\ResponseReaders;

use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
use Psr\Http\Message\ResponseInterface;

class JsonResponseReader implements IResponseReader
{
    /**
     * @param ResponseInterface $response
     * @return array
     */
    public function read(ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();
        return json_decode($body, true);
    }
}
