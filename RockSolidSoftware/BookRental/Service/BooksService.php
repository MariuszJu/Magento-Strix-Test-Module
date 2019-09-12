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

    /**
     * Book Repository instance
     *
     * @var BookRepositoryInterface
     */
    protected $bookRepository;

    /**
     * Customer Book Repository instance
     *
     * @var CustomerBookRepositoryInterface
     */
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
     * Get Books with pagination
     *
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
     * Get Books count
     *
     * @return int
     */
    public function getBooksCount(): int
    {
        return $this->bookRepository->getEntitiesCount();
    }

    /**
     * Get Book by ID or slug
     *
     * @throws \RuntimeException if invalid book parameter sent (neither valid integer nor string) or Book was not found
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
     * Check weather given book is taken, Book ID or slug can be passed
     *
     * @throws \RuntimeException if Book was not found
     * @param mixed $book
     * @return bool
     */
    public function isBookTaken($book): bool
    {
        return !empty($this->getBookLender($book));
    }

    /**
     * Get Customer Book entry for given noo, Book ID or slug can be passed
     *
     * @throws \RuntimeException if given Book was not found
     * @param mixed $book
     * @return CustomerBookInterface|null
     */
    public function getBookLender($book): ?CustomerBookInterface
    {
        $book = $this->getBook($book, false);

        try {
            $customerBook = $this->customerBookRepository->getByBookId($book->getId());
        } catch (\Exception $e) {
            return null;
        }

        return $customerBook;
    }

    /**
     * Get Customer Book entries for given Book
     *
     * @throws \RuntimeException if Book was not found
     * @param mixed $book
     * @return array
     */
    public function getBookHistory($book): array
    {
        return $this->customerBookRepository->getBookHistory(
            is_numeric($book) ? $book : $this->getBook($book)->getId()
        );
    }

}
