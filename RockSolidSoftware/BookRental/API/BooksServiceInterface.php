<?php

namespace RockSolidSoftware\BookRental\API;

use Magento\Framework\DataObject;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;

interface BooksServiceInterface
{

    /**
     * Get Books with pagination
     *
     * @param int        $page
     * @param int        $perPage
     * @param array|null $order
     * @return DataObject[]
     */
    public function getBooks(int $page, int $perPage, array $order = null): array;

    /**
     * Get Books count
     *
     * @return int
     */
    public function getBooksCount(): int;

    /**
     * Get Book by Book ID or slug
     *
     * @param mixed $book
     * @param bool  $injectLender
     * @return BookInterface
     */
    public function getBook($book, bool $injectLender = false): BookInterface;

    /**
     * Check whether Book is already rented by someone
     *
     * @param mixed $book
     * @return bool
     */
    public function isBookTaken($book): bool;

    /**
     * Get Customer Book entry for given Book
     *
     * @param $book
     * @return CustomerBookInterface|null
     */
    public function getBookLender($book): ?CustomerBookInterface;

}
