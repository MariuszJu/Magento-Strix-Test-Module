<?php

namespace RockSolidSoftware\BookRental\API;

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

}
