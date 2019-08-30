<?php

namespace RockSolidSoftware\BookRental\Model;

use RockSolidSoftware\BookRental\Model\BookFactory;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\BookRepositoryInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\Collection;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book as BookResource;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\CollectionFactory;
use RockSolidSoftware\BookRental\Model\ResourceModel\BookFactory as BookResourceFactory;

class BookRepository implements BookRepositoryInterface
{

    /** @var Book */
    private $book;

    /** @var BookResource */
    private $resource;

    /** @var Collection */
    private $collection;

    /**
     * BookRepository constructor
     *
     * @param BookFactory         $bookFactory
     * @param BookResourceFactory $bookResourceFactory
     * @param CollectionFactory   $collectionFactory
     */
    public function __construct(BookFactory $bookFactory, BookResourceFactory $bookResourceFactory, CollectionFactory $collectionFactory)
    {
        $this->book = $bookFactory->create();
        $this->resource = $bookResourceFactory->create();
        $this->collection = $collectionFactory->create();
    }

    /**
     * @throws \Exception
     * @param array|BookInterface $entity
     * @return int
     */
    public function save($entity): int
    {
        if ($entity instanceof BookInterface) {
            return $entity->save()->getId();
        } else {
            $entity = (clone $this->book)->setData($entity);
            $this->resource->save($entity);

            return $entity->getId();
        }
    }

    /**
     * @throws \RuntimeException
     * @param int $id
     * @return BookInterface
     */
    public function getById(int $id): BookInterface
    {
        $entity = clone $this->book;
        $this->resource->load($entity, $id, 'id');

        if (!$entity->getId()) {
            throw new \RuntimeException(sprintf('Book with the %s ID does not exist', $id));
        }

        return $entity;
    }

    /**
     * @return BookInterface|null
     */
    public function last(): ?BookInterface
    {
        $collection = $this->collection->setOrder('id', 'DESC');

        return $collection->getSize() ? $collection->getFirstItem() : null;
    }

    /**
     * @throws \RuntimeException
     * @param BookInterface $entity
     * @return bool
     */
    public function delete(BookInterface $entity): bool
    {
        try {
            $this->resource->delete($entity);
        } catch (\Throwable $e) {
            throw new \RuntimeException(sprintf('Could not delete entity: %s', $e->getMessage()));
        }

        return true;
    }

    /**
     * @throws \RuntimeException
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

}
