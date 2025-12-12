<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud\Event;

use sdeller\CrudBundle\Crud\Repository\EntityInterface;
use Symfony\Contracts\EventDispatcher\Event;

class GetEvent extends Event
{
    public function __construct(
        private readonly ?EntityInterface $entity,
    ) {
    }

    public function getEntity(): ?EntityInterface
    {
        return $this->entity;
    }
}
