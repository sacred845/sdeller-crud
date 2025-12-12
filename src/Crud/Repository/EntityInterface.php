<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud\Repository;

interface EntityInterface
{
    public function onUpdate(): void;

    public function onDelete(): void;

    public function onActive(bool $active): void;

    public function isCanUpdate(): bool;

    public function isCanDelete(): bool;

    public function isDeleted(): bool;
}
