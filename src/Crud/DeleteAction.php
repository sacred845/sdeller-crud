<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Event\DeleteEvent;
use sdeller\CrudBundle\Crud\Exception\EntityCanNotEditException;
use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class DeleteAction implements DeleteActionInterface
{
    private ActionRepositoryInterface $repository;
    private bool $isDisableEvent = false;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(DeleteActionDtoInterface $dto, ?callable $before = null): void
    {
        foreach ($dto->getId() as $id) {
            $entity = $this->repository->getEntityById($id);

            if (null == $entity || $entity->isDeleted()) {
                continue;
            }

            if (!$entity->isCanDelete()) {
                throw new EntityCanNotEditException('Entity can not delete');
            }

            if (null !== $before) {
                $before($entity, $this->repository);
            }

            if (!$this->isDisableEvent) {
                $this->eventDispatcher->dispatch(new DeleteEvent($entity), DeleteEvent::NAME);
            }

            $this->repository->deleteEntity($entity, $dto);
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
