<?php

namespace RockSolidSoftware\BookRental\Model;

use Magento\Framework\DataObject;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\Data\EntityInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;
use RockSolidSoftware\BookRental\API\CustomerBookRepositoryInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook\Collection;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook\CollectionFactory;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook as CustomerBookResource;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBookFactory as CustomerBookResourceFactory;

class CustomerBookRepository implements CustomerBookRepositoryInterface
{

    /** @var CustomerBookInterface */
    private $customerBook;

    /** @var CustomerBookResource */
    private $resource;

    /** @var Collection */
    private $collection;

    /**
     * CustomerBookRepository constructor
     *
     * @param CustomerBookInterface       $customerBook
     * @param CustomerBookResourceFactory $bookResourceFactory
     * @param CollectionFactory           $collectionFactory
     */
    public function __construct(CustomerBookInterface $customerBook, CustomerBookResourceFactory $bookResourceFactory, CollectionFactory $collectionFactory)
    {
        $this->customerBook = $customerBook;
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
        if (!$entity instanceof CustomerBookInterface) {
            $entity = (clone $this->customerBook)->setData($entity);
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
        $entity = clone $this->customerBook;
        $this->resource->load($entity, $id, 'id');

        if (!$entity->getId()) {
            throw new \RuntimeException(sprintf('Book with the %s ID does not exist', $id));
        }

        return $entity;
    }

    /**
     * @throws \RuntimeException
     * @param int $bookId
     * @return CustomerBookInterface
     */
    public function getByBookId(int $bookId): CustomerBookInterface
    {
//        $entity = clone $this->customerBook;
//        $this->resource->load($entity, $bookId, 'book_id');

        $collection = (clone $this->collection);
        $collection->getSelect()
            ->joinLeft(
                ['customer' => 'customer_entity'],
                'customer.entity_id = main_table.customer_id', [
                    'customer_email'     => 'customer.email',
                    'customer_firstname' => 'customer.firstname',
                    'customer_lastname'  => 'customer.lastname',
                ]
            );

        $entity = $collection->addFilter('book_id', $bookId)
            ->addFilter('is_rented', 1)
            ->getFirstItem();

        if (!$entity || !$entity->getId()) {
            throw new \RuntimeException(sprintf('Customer book entity for book ID %s does not exist', $bookId));
        }

        return $entity;
    }

    /**
     * @param int       $customerId
     * @param bool|null $onlyRented
     * @return DataObject[]
     */
    public function getByCustomerId(int $customerId, bool $onlyRented = null): array
    {
        $collection = clone $this->collection;

        $collection->getSelect()
            ->joinLeft(
                ['book' => Book::table],
                'book.id = main_table.book_id', [
                    'book_title'  => 'book.title',
                    'book_author' => 'book.author',
                    'book_slug'   => 'book.slug',
                ]
            );

        $collection->addFilter('customer_id', $customerId);

        if (!is_null($onlyRented)) {
            $collection->addFilter('is_rented', (int) $onlyRented);
        }

        return $collection->getItems();
    }

    /**
     * @return DataObject[]
     */
    public function all(): array
    {
        return (clone $this->collection)->getItems();
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
