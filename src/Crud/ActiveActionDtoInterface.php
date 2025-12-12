<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

interface ActiveActionDtoInterface
{
    /**
     * @return int[]
     */
    public function getId(): array;

    public function isActive(): bool;
}
