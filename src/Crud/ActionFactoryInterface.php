<?php

declare(strict_types=1);

namespace sdeller\CrudBundle\Crud;

use sdeller\CrudBundle\Crud\Repository\ActionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface ActionFactoryInterface
{
    public function makeInsert(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): InsertActionInterface;

    public function makeUpdate(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): UpdateActionInterface;

    public function makeDelete(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): DeleteActionInterface;

    public function makeActive(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): ActiveActionInterface;

    public function makeGet(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): GetActionInterface;

    public function makeGetNotDelete(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): GetActionInterface;

    public function makeGetActive(ActionRepositoryInterface $repository, EventDispatcherInterface $eventDispatcher): GetActionInterface;
}
