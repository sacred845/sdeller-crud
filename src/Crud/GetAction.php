<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Event\GetEvent;
use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use sdeller\CrudBundle\Crud\Repository\EntityInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class GetAction implements GetActionInterface
{
    private ActionRepositoryInterface $repository;
    private bool $isDisableEvent = false;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(GetActionDtoInterface $dto, ?callable $after = null): ?EntityInterface
    {
        $entity = $this->repository->getEntityById($dto->getId());

        if (null !== $after) {
            $after($entity, $this->repository);
        }

        if (!$this->isDisableEvent) {
            $this->eventDispatcher->dispatch(new GetEvent($entity), 'crud.get.after');
        }

        return $entity;
    }

    public function setRepository(ActionRepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function disableEvent(): void
    {
        $this->isDisableEvent = true;
    }
}
