<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use sdeller\CrudBundle\Crud\Repository\EntityInterface;

interface GetActionInterface
{
    public function execute(GetActionDtoInterface $dto, ?callable $after = null): ?EntityInterface;

    public function setRepository(ActionRepositoryInterface $repository): void;

    public function disableEvent(): void;
}
