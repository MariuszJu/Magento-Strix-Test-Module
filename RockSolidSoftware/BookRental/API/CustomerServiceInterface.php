<?php

namespace RockSolidSoftware\BookRental\API;

use RockSolidSoftware\BookRental\API\Data\BookInterface;

interface CustomerServiceInterface
{

    /**
     * Check whether customer is able to rent a book
     *
     * @param int|null $customerId
     * @return bool
     */
    public function canCustomerRentBook(int $customerId = null): bool;

    /**
     * Get currently logged in Customer ID
     *
     * @return int|null
     */
    public function customerId(): ?int;

    /**
     * Check whether customer is logged in
     *
     * @return bool
     */
    public function isCustomerLoggedIn(): bool;

    /**
     * Check if customer is authenticated and redirect him to given URL if it's not
     *
     * @param string|null $afterAuthUrl
     */
    public function authenticateCustomer(string $afterAuthUrl = null);

    /**
     * Rent given Book by given Customer
     *
     * @param BookInterface $book
     * @param int|null      $customerId
     */
    public function rentBook(BookInterface $book, int $customerId = null);

    /**
     * Return given Book by given Customer
     *
     * @param BookInterface $book
     * @param int|null $customerId
     */
    public function returnBook(BookInterface $book, int $customerId = null);

    /**
     * Get all Books rented by given Customer
     *
     * @param int|null $customerId
     * @return mixed
     */
    public function customerBooks(int $customerId = null);

}
