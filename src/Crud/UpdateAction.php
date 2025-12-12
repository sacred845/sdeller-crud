<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Event\UpdateEvent;
use sdeller\CrudBundle\Crud\Exception\EntityNotValidException;
use sdeller\CrudBundle\Crud\Exception\EntityCanNotEditException;
use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use sdeller\CrudBundle\Crud\Repository\EntityInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateAction implements UpdateActionInterface
{
    private ActionRepositoryInterface $repository;
    private bool $isDisableEvent = false;

    private EntityInterface $entity;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function execute(UpdateActionDtoInterface $dto, ?callable $before = null, ?callable $after = null): void
    {
        $entity = $this->repository->getEntityById($dto->getId());

        if (null === $entity || $entity->isDeleted()) {
            throw new EntityCanNotEditException('Entity not exist');
        }

        $oldEntity = clone $entity;

        if (!$entity->isCanUpdate()) {
            throw new EntityCanNotEditException('Entity can not update');
        }

        if (null !== $before) {
            $before($entity, $this->repository);
        }

        $errors = $this->validator->validate($entity);
        if (0 !== \count($errors)) {
            throw new EntityNotValidException((string) $errors);
        }

        $this->repository->updateEntityFromDto($entity, $dto);

        if (null !== $after) {
            $after($entity, $this->repository);
        }

        if (!$this->isDisableEvent) {
            $this->eventDispatcher->dispatch(new UpdateEvent($entity, $oldEntity), UpdateEvent::NAME);
        }

        $this->entity = $entity;
    }

    public function setRepository(ActionRepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function disableEvent(): void
    {
        $this->isDisableEvent = true;
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}
