<?php declare(strict_types=1);

/**
 * Содержит логику непосредственного выполнения запроса и дает инструмент для чтения ответа на запрос
 */
namespace AfSergiu\ApiInvoker\Contracts\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface IRequestInvoker
{
    /**
     * Вызывает запрос
     * @param RequestInterface $request
     * @return IRequestInvoker
     */
    public function invoke(RequestInterface $request): IRequestInvoker;

    /**
     * Читает ответ на запрос
     * @param IResponseReader $reader
     * @return mixed
     */
    public function read(IResponseReader $reader);
}
