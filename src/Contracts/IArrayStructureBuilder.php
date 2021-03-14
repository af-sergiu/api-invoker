<?php declare(strict_types=1);
/**
 * Конструирует массив определенного формата
 */

namespace AfSergiu\ApiInvoker\Contracts;

interface IArrayStructureBuilder
{
    /**
     * @return array
     */
    public function build(): array;
}
