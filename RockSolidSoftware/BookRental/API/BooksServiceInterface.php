<?php

namespace RockSolidSoftware\BookRental\API;

use Magento\Framework\DataObject;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;

interface BooksServiceInterface
{

    /**
     * @param int        $page
     * @param int        $perPage
     * @param array|null $order
     * @return DataObject[]
     */
    public function getBooks(int $page, int $perPage, array $order = null): array;

    /**
     * @return int
     */
    public function getBooksCount(): int;

    /**
     * @param mixed $book
     * @param bool  $injectLender
     * @return BookInterface
     */
    public function getBook($book, bool $injectLender = false): BookInterface;

    /**
     * @param mixed $book
     * @return bool
     */
    public function isBookTaken($book): bool;

    /**
     * @param $book
     * @return CustomerBookInterface|null
     */
    public function getBookLender($book): ?CustomerBookInterface;

}
