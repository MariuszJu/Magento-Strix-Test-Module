<?php

namespace RockSolidSoftware\BookRental\API;

use RockSolidSoftware\BookRental\API\Data\EntityInterface;

interface RepositoryInterface
{

    /**
     * @param array|EntityInterface $entity
     * @return int
     */
    public function save($entity): int;

    /**
     * @param int $id
     * @return EntityInterface
     */
    public function getById(int $id): EntityInterface;

    /**
     * @param EntityInterface $entity
     * @return bool
     */
    public function delete(EntityInterface $entity): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;

    /**
     * @return EntityInterface|null
     */
    public function last(): ?EntityInterface;

    /**
     * @return mixed
     */
    public function all();

    /**
     * @return int
     */
    public function getEntitiesCount(): int;

}
