<?php

namespace RockSolidSoftware\BookRental\API;

use RockSolidSoftware\BookRental\API\Data\BookInterface;

interface CustomerServiceInterface
{

    /**
     * @param int|null $customerId
     * @return bool
     */
    public function canCustomerRentBook(int $customerId = null): bool;

    /**
     * @return int|null
     */
    public function customerId(): ?int;

    /**
     * @return bool
     */
    public function isCustomerLoggedIn(): bool;

    /**
     * @param string|null $afterAuthUrl
     */
    public function authenticateCustomer(string $afterAuthUrl = null);

    /**
     * @param BookInterface $book
     * @param int|null      $customerId
     */
    public function rentBook(BookInterface $book, int $customerId = null);

    /**
     * @param BookInterface $book
     * @param int|null $customerId
     */
    public function returnBook(BookInterface $book, int $customerId = null);

    /**
     * @param int|null $customerId
     * @return mixed
     */
    public function customerBooks(int $customerId = null);

}
