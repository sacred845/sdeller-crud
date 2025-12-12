<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use sdeller\CrudBundle\Crud\Repository\EntityInterface;

interface UpdateActionInterface
{
    public function execute(UpdateActionDtoInterface $dto, ?callable $before = null, ?callable $after = null): void;

    public function setRepository(ActionRepositoryInterface $repository): void;

    public function disableEvent(): void;

    public function getEntity(): EntityInterface;
}
