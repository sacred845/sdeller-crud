<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud\Event;

use sdeller\CrudBundle\Crud\Repository\EntityInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class UpdateEvent extends Event
{
    public const NAME = 'sdeller.crud.update.after';

    public function __construct(
        private readonly EntityInterface $entity,
        private readonly EntityInterface $oldEntity,
    ) {
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }

    public function getOldEntity(): EntityInterface
    {
        return $this->oldEntity;
    }
}
