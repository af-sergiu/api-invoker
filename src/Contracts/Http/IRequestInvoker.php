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
     * @return ResponseInterface
     */
    public function invoke(RequestInterface $request): ResponseInterface;
}
