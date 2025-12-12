<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

interface DeleteActionDtoInterface
{
    /**
     * @return int[]
     */
    public function getId(): array;
}
