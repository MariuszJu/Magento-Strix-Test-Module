<?php

namespace RockSolidSoftware\BookRental\API\Data;

interface BookInterface extends EntityInterface
{

    /**
     * Get or set Customer Book entry
     *
     * @param CustomerBookInterface|null $customerBook
     * @return CustomerBookInterface|null
     */
    public function customerBook(?CustomerBookInterface $customerBook = null): ?CustomerBookInterface;

    /**
     * Check whether Book is already taken by someone
     *
     * @return bool
     */
    public function isTaken(): bool;

}
