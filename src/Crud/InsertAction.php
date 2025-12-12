<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Event\InsertEvent;
use sdeller\CrudBundle\Crud\Exception\EntityNotValidException;
use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use sdeller\CrudBundle\Crud\Repository\EntityInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class InsertAction implements InsertActionInterface
{
    private ActionRepositoryInterface $repository;
    private bool $isDisableEvent = false;

    private EntityInterface $entity;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function execute(InsertActionDtoInterface $dto, ?callable $before = null, ?callable $after = null): void
    {
        $entity = $this->repository->makeEntity($dto);

        if (null !== $before) {
            $before($entity, $this->repository);
        }

        $errors = $this->validator->validate($entity);
        if (0 !== \count($errors)) {
            throw new EntityNotValidException((string) $errors);
        }

        $this->repository->insert($entity);
        if (null !== $after) {
            $after($entity, $this->repository);
        }

        if (!$this->isDisableEvent) {
            $this->eventDispatcher->dispatch(new InsertEvent($entity), InsertEvent::NAME);
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
