<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud\Event;

use sdeller\CrudBundle\Crud\Repository\EntityInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class InsertEvent extends Event
{
    public const NAME = 'sdeller.crud.insert.after';

    public function __construct(
        private readonly EntityInterface $entity,
    ) {
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}
