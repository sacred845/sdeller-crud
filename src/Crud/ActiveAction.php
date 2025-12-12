<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Event\ActiveEvent;
use sdeller\CrudBundle\Crud\Exception\EntityCanNotEditException;
use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ActiveAction implements ActiveActionInterface
{
    private ActionRepositoryInterface $repository;
    private bool $isDisableEvent = false;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(ActiveActionDtoInterface $dto, ?callable $before = null, ?callable $after = null): void
    {
        foreach ($dto->getId() as $id) {
            $entity = $this->repository->getEntityById($id);

            if (null === $entity || $entity->isDeleted()) {
                continue;
            }

            if (!$entity->isCanUpdate()) {
                throw new EntityCanNotEditException('Entity can not update');
            }

            if (null !== $before) {
                $before($entity, $this->repository);
            }

            $this->repository->activeEntity($entity, $dto);

            if (null !== $after) {
                $after($entity, $this->repository);
            }

            if (!$this->isDisableEvent) {
                $this->eventDispatcher->dispatch(new ActiveEvent($entity), 'crud.active.after');
            }
        }
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
