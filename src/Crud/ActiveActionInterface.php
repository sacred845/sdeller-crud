<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;

interface ActiveActionInterface
{
    public function execute(ActiveActionDtoInterface $dto, ?callable $before = null, ?callable $after = null): void;

    public function setRepository(ActionRepositoryInterface $repository): void;

    public function disableEvent(): void;
}
