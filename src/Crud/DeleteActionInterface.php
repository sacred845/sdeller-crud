<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;

interface DeleteActionInterface
{
    public function execute(DeleteActionDtoInterface $dto, ?callable $before = null): void;

    public function setRepository(ActionRepositoryInterface $repository): void;

    public function disableEvent(): void;
}
