<?php

namespace RockSolidSoftware\BookRental\API;

use RockSolidSoftware\BookRental\API\Data\BookInterface;

interface BookRepositoryInterface
{

    /**
     * @param array|BookInterface $entity
     * @return int
     */
    public function save($entity): int;

    /**
     * @param int $id
     * @return BookInterface
     */
    public function getById(int $id): BookInterface;

    /**
     * @param BookInterface $entity
     * @return bool
     */
    public function delete(BookInterface $entity): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;

    /**
     * @return BookInterface|null
     */
    public function last(): ?BookInterface;

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param int        $page
     * @param int        $perPage
     * @param array|null $order
     * @return mixed
     */
    public function getPage(int $page, int $perPage = 10, array $order = null);

    /**
     * @return int
     */
    public function getEntitiesCount(): int;

}
