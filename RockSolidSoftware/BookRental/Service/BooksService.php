<?php

namespace RockSolidSoftware\BookRental\Service;

use Magento\Framework\DataObject;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use RockSolidSoftware\BookRental\API\BookRepositoryInterface;

class BooksService implements BooksServiceInterface
{

    /** @var BookRepositoryInterface */
    protected $bookRepository;

    /**
     * BooksService constructor
     *
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param int        $page
     * @param int        $perPage
     * @param array|null $order
     * @return DataObject[]
     */
    public function getBooks(int $page, int $perPage, array $order = null): array
    {
        return $this->bookRepository->getPage($page, $perPage, $order);
    }

    /**
     * @return int
     */
    public function getBooksCount(): int
    {
        return $this->bookRepository->getEntitiesCount();
    }

}
