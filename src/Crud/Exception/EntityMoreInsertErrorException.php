<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud\Exception;

class EntityMoreInsertErrorException extends \LogicException
{
    private string $item;

    public function getItem(): string
    {
        return $this->item;
    }

    public function setItem(string $item): void
    {
        $this->item = $item;
    }
}
