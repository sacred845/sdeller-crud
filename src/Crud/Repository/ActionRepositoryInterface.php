<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud\Repository;

use sdeller\CrudBundle\Crud\ActiveActionDtoInterface;
use sdeller\CrudBundle\Crud\DeleteActionDtoInterface;
use sdeller\CrudBundle\Crud\InsertActionDtoInterface;
use sdeller\CrudBundle\Crud\UpdateActionDtoInterface;

interface ActionRepositoryInterface
{
    public function makeEntity(InsertActionDtoInterface $dto): EntityInterface;

    public function getEntityById(int $id): ?EntityInterface;

    public function getNotDeletedEntity(int $id): ?EntityInterface;

    public function getActiveEntity(int $id): ?EntityInterface;

    public function insert(EntityInterface $entity): void;

    public function updateEntityFromDto(EntityInterface $entity, UpdateActionDtoInterface $dto): void;

    public function deleteEntity(EntityInterface $entity, DeleteActionDtoInterface $dto): void;

    public function activeEntity(EntityInterface $entity, ActiveActionDtoInterface $dto): void;

    public function tuneEntity(EntityInterface $entity, string $item, InsertActionDtoInterface $dto): void;
}
