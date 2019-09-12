<?php

namespace RockSolidSoftware\BookRental\Model;

use Magento\Framework\DataObject;
use RockSolidSoftware\BookRental\Model\BookFactory;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\Data\EntityInterface;
use RockSolidSoftware\BookRental\API\BookRepositoryInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\Collection;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book as BookResource;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\CollectionFactory;
use RockSolidSoftware\BookRental\Model\ResourceModel\BookFactory as BookResourceFactory;

class BookRepository implements BookRepositoryInterface
{

    /** @var BookInterface */
    private $book;

    /** @var BookResource */
    private $resource;

    /** @var Collection */
    private $collection;

    /**
     * BookRepository constructor
     *
     * @param BookInterface       $book
     * @param BookResourceFactory $bookResourceFactory
     * @param CollectionFactory   $collectionFactory
     */
    public function __construct(BookInterface $book, BookResourceFactory $bookResourceFactory, CollectionFactory $collectionFactory)
    {
        $this->book = $book;
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
        if (!$entity instanceof BookInterface) {
            $entity = (clone $this->book)->setData($entity);
        }

        $this->resource->save($entity);

        return $entity->getId();
    }

    /**
     * @throws \RuntimeException
     * @param int $id
     * @return EntityInterface
     */
    public function getById(int $id): EntityInterface
    {
        $entity = clone $this->book;
        $this->resource->load($entity, $id, 'id');

        if (!$entity->getId()) {
            throw new \RuntimeException(sprintf('Book with the %s ID does not exist', $id));
        }

        return $entity;
    }

    /**
     * @throws \RuntimeException
     * @param string $slug
     * @return BookInterface
     */
    public function getBySlug(string $slug): BookInterface
    {
        $entity = clone $this->book;
        $this->resource->load($entity, $slug, 'slug');

        if (!$entity->getId()) {
            throw new \RuntimeException(sprintf('Book with the slug %s does not exist', $slug));
        }

        return $entity;
    }

    /**
     * @return DataObject[]
     */
    public function all(): array
    {
        return (clone $this->collection)
            ->clear()
            ->getItems();
    }

    /**
     * @param int        $page
     * @param int        $perPage
     * @param array|null $order
     * @return DataObject[]
     */
    public function getPage(int $page, int $perPage = 10, array $order = null): array
    {
        $collection = clone $this->collection;

        $collection->getSelect()
            ->joinLeft(
                ['cb' => CustomerBook::table],
                'cb.book_id = main_table.id AND cb.is_rented = 1', [
                    'customer_id' => 'cb.customer_id',
                    'is_rented'   => 'cb.is_rented',
                ]
            );
//            ->joinLeft(
//                ['customer' => 'customer_entity'],
//                'customer.entity_id = cb.customer_id', [
//                    'customer_email'     => 'customer.email',
//                    'customer_firstname' => 'customer.firstname',
//                    'customer_lastname'  => 'customer.lastname',
//                ]
//            );

        is_array($order) && !empty($order) && $collection->setOrder(key($order), reset($order));

        $collection->setPageSize($perPage);
        $collection->setCurPage($page);

        return $collection->getItems();
    }

    /**
     * @return int
     */
    public function getEntitiesCount(): int
    {
        return (clone $this->collection)->getSize();
    }

    /**
     * @return BookInterface|null
     */
    public function last(): ?EntityInterface
    {
        $collection = (clone $this->collection)->setOrder('id', 'DESC');

        return $collection->getSize() ? $collection->getFirstItem() : null;
    }

    /**
     * @throws \RuntimeException
     * @param EntityInterface $entity
     * @return bool
     */
    public function delete(EntityInterface $entity): bool
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
