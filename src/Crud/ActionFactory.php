<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ActionFactory implements ActionFactoryInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function makeInsert(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): InsertAction
    {
        $insert = new InsertAction($eventDispatcher, $this->validator);
        $insert->setRepository($repository);

        return $insert;
    }

    public function makeUpdate(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): UpdateAction
    {
        $update = new UpdateAction($eventDispatcher, $this->validator);
        $update->setRepository($repository);

        return $update;
    }

    public function makeDelete(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): DeleteAction
    {
        $delete = new DeleteAction($eventDispatcher);
        $delete->setRepository($repository);

        return $delete;
    }

    public function makeActive(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): ActiveAction
    {
        $active = new ActiveAction($eventDispatcher);
        $active->setRepository($repository);

        return $active;
    }

    public function makeGet(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): GetAction
    {
        $get = new GetAction($eventDispatcher);
        $get->setRepository($repository);

        return $get;
    }

    public function makeGetNotDelete(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): GetNotDeleteAction
    {
        $get = new GetNotDeleteAction($eventDispatcher);
        $get->setRepository($repository);

        return $get;
    }

    public function makeGetActive(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): GetActiveAction
    {
        $get = new GetActiveAction($eventDispatcher);
        $get->setRepository($repository);

        return $get;
    }

    public function makeMoreInsert(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): MoreInsertActionInterface
    {
        $insert = new MoreInsertAction($eventDispatcher, $this->validator);
        $insert->setRepository($repository);

        return $insert;
    }
}
