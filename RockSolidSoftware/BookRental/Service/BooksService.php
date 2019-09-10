<?php

namespace RockSolidSoftware\BookRental\Service;

use Magento\Framework\DataObject;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use RockSolidSoftware\BookRental\API\BookRepositoryInterface;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;
use RockSolidSoftware\BookRental\API\CustomerBookRepositoryInterface;

class BooksService implements BooksServiceInterface
{

    /** @var BookRepositoryInterface */
    protected $bookRepository;

    /** @var CustomerBookRepositoryInterface */
    private $customerBookRepository;

    /**
     * BooksService constructor
     *
     * @param BookRepositoryInterface         $bookRepository
     * @param CustomerBookRepositoryInterface $customerBookRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository, CustomerBookRepositoryInterface $customerBookRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->customerBookRepository = $customerBookRepository;
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

    /**
     * @throws \RuntimeException
     * @param mixed $book
     * @param bool  $injectLender
     * @return BookInterface
     */
    public function getBook($book, bool $injectLender = false): BookInterface
    {
        switch (true) {
            case is_numeric($book):
                $book = $this->bookRepository->getById($book);
                break;

            case is_string($book):
                $book = $this->bookRepository->getBySlug($book);
                break;

            case $book instanceof BookInterface;
                break;

            default:
                throw new \RuntimeException(__('Book parameter must be a valid integer or string'));
        }

        if ($injectLender) {
            $book->customerBook($this->getBookLender($book));
        }

        return $book;
    }

    /**
     * @throws \RuntimeException
     * @param mixed $book
     * @return bool
     */
    public function isBookTaken($book): bool
    {
        return !empty($this->getBookLender($book));
    }

    /**
     * @throws \RuntimeException
     * @param mixed $book
     * @return CustomerBookInterface|null
     */
    public function getBookLender($book): ?CustomerBookInterface
    {
        try {
            $customerBook = $this->customerBookRepository->getByBookId(
                $this->getBook($book, false)->getId()
            );
        } catch (\RuntimeException $e) {
            return null;
        }

        return $customerBook;
    }

}
