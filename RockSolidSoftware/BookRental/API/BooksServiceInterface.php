<?php

namespace RockSolidSoftware\BookRental\API;

use Magento\Framework\DataObject;

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
     * @param int        $page
     * @param int        $perPage
     * @param array|null $order
     * @return array
     */
    public function getBooksPagination(int $page, int $perPage, array $order = null): array;

}
