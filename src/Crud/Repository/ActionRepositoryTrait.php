<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud\Repository;

use sdeller\CrudBundle\Crud\ActiveActionDtoInterface;
use sdeller\CrudBundle\Crud\Attributes\InsertProperty;
use sdeller\CrudBundle\Crud\Attributes\UpdateProperty;
use sdeller\CrudBundle\Crud\DeleteActionDtoInterface;
use sdeller\CrudBundle\Crud\Exception\HasNotMethodForUpdateException;
use sdeller\CrudBundle\Crud\InsertActionDtoInterface;
use sdeller\CrudBundle\Crud\UpdateActionDtoInterface;

trait ActionRepositoryTrait
{
    public function insert(EntityInterface $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function getEntityById(int $id): ?EntityInterface
    {
        return $this->find($id);
    }

    public function getNotDeletedEntity(int $id): ?EntityInterface
    {
        return $this->findOneBy([
            'id' => $id,
        ]);
    }

    public function getActiveEntity(int $id): ?EntityInterface
    {
        return $this->findOneBy([
            'id' => $id,
        ]);
    }

    public function updateEntityFromDto(EntityInterface $entity, UpdateActionDtoInterface $dto): void
    {
        $rfDto = new \ReflectionClass($dto);

        foreach ($rfDto->getProperties() as $property) {
            if (0 === \count($property->getAttributes(UpdateProperty::class))) {
                continue;
            }

            $field = $property->getName();
            $getMethod = \sprintf('get%s', \ucfirst($field));
            $setMethod = \sprintf('set%s', \ucfirst($field));

            $this->validateMethodsForUpdate($field, $entity, $dto);

            $isHasPublicDtoProperty = $rfDto->hasProperty($field);
            if ($isHasPublicDtoProperty) {
                $isHasPublicDtoProperty &= $rfDto->getProperty($field)->isPublic();
            }

            if ($isHasPublicDtoProperty) {
                $entity->$setMethod($dto->$field);
            } else {
                $entity->$setMethod($dto->$getMethod());
            }
        }
        $entity->onUpdate();

        $this->getEntityManager()->flush();
    }

    public function deleteEntity(EntityInterface $entity, DeleteActionDtoInterface $dto): void
    {
        $entity->onDelete();
        $this->getEntityManager()->flush();
    }

    public function activeEntity(EntityInterface $entity, ActiveActionDtoInterface $dto): void
    {
        $entity->onActive($dto->isActive());
        $this->getEntityManager()->flush();
    }

    public function tuneEntity(EntityInterface $entity, string $item, InsertActionDtoInterface $dto): void
    {
    }

    protected function validateMethodsForUpdate(
        string $field,
        EntityInterface $entity,
        UpdateActionDtoInterface|InsertActionDtoInterface $dto,
    ): void {
        $rfEntity = new \ReflectionClass($entity);
        $rfDto = new \ReflectionClass($dto);

        $getMethod = \sprintf('get%s', \ucfirst($field));
        $setMethod = \sprintf('set%s', \ucfirst($field));

        $isHasPublicEntityMethod = $rfEntity->hasMethod($setMethod);
        if ($isHasPublicEntityMethod) {
            $isHasPublicEntityMethod &= $rfEntity->getMethod($setMethod)->isPublic();
        }
        if (!$isHasPublicEntityMethod) {
            throw new HasNotMethodForUpdateException(\sprintf('Update failed. Dto class invalid. Method %s not defined', $setMethod));
        }

        $isHasPublicDtoProperty = $rfDto->hasProperty($field);
        if ($isHasPublicDtoProperty) {
            $isHasPublicDtoProperty &= $rfDto->getProperty($field)->isPublic();
        }
        $isHasPublicDtoMethod = $rfDto->hasMethod($getMethod);
        if ($isHasPublicDtoMethod) {
            $isHasPublicDtoMethod &= $rfDto->getMethod($getMethod)->isPublic();
        }
        if (!$isHasPublicDtoMethod && !$isHasPublicDtoProperty) {
            throw new HasNotMethodForUpdateException(\sprintf('Update failed. Dto class invalid. Public method %s or property %s not defined', $setMethod, $field));
        }

        $methodEntity = $rfEntity->getMethod($setMethod);

        $dtoVarType = ($isHasPublicDtoProperty) ?
            // @phpstan-ignore-next-line
            $rfDto->getProperty($field)->getType()?->getName() :
            // @phpstan-ignore-next-line
            $rfDto->getMethod($getMethod)->getReturnType()?->getName();

        $parameters = $methodEntity->getParameters();
        if (($parameters[0] ?? null) === null) {
            throw new HasNotMethodForUpdateException(\sprintf('Update failed. Dto class invalid. Invalid type %s method', $setMethod));
        }

        // @phpstan-ignore-next-line
        $entityVarType = $parameters[0]->getType()?->getName();
        if (null === $entityVarType) {
            throw new HasNotMethodForUpdateException(\sprintf('Update failed. Dto class invalid. Invalid type %s method', $getMethod));
        }

        if ($entityVarType !== $dtoVarType) {
            throw new HasNotMethodForUpdateException(\sprintf('Update failed. Dto class invalid types. %s <> %s', $entityVarType, $dtoVarType));
        }
    }

    protected function loadEntityFromDto(EntityInterface $entity, InsertActionDtoInterface $dto): void
    {
        $rfDto = new \ReflectionClass($dto);

        foreach ($rfDto->getProperties() as $property) {
            if (0 === \count($property->getAttributes(InsertProperty::class))) {
                continue;
            }

            $field = $property->getName();
            $getMethod = \sprintf('get%s', \ucfirst($field));
            $setMethod = \sprintf('set%s', \ucfirst($field));

            $this->validateMethodsForUpdate($field, $entity, $dto);

            $isHasPublicDtoProperty = $rfDto->hasProperty($field);
            if ($isHasPublicDtoProperty) {
                $isHasPublicDtoProperty &= $rfDto->getProperty($field)->isPublic();
            }

            if ($isHasPublicDtoProperty) {
                $entity->$setMethod($dto->$field);
            } else {
                $entity->$setMethod($dto->$getMethod());
            }
        }
        $entity->onUpdate();

        $this->getEntityManager()->flush();
    }
}
