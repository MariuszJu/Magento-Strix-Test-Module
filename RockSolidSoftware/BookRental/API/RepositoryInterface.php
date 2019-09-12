<?php

namespace RockSolidSoftware\BookRental\API;

use RockSolidSoftware\BookRental\API\Data\EntityInterface;

interface RepositoryInterface
{

    /**
     * Create or update entity
     *
     * @param array|EntityInterface $entity
     * @return int
     */
    public function save($entity): int;

    /**
     * Get entity by its ID
     *
     * @param int $id
     * @return EntityInterface
     */
    public function getById(int $id): EntityInterface;

    /**
     * Delete entity
     *
     * @param EntityInterface $entity
     * @return bool
     */
    public function delete(EntityInterface $entity): bool;

    /**
     * Delete entity by its ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;

    /**
     * Get last inserted entity
     *
     * @return EntityInterface|null
     */
    public function last(): ?EntityInterface;

    /**
     * Get all entities
     *
     * @return mixed
     */
    public function all();

    /**
     * Get entities count
     *
     * @return int
     */
    public function getEntitiesCount(): int;

}
