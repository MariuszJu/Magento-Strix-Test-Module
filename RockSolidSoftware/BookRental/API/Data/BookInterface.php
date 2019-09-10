<?php

namespace RockSolidSoftware\BookRental\API\Data;

interface BookInterface extends EntityInterface
{

    /**
     * @param CustomerBookInterface|null $customerBook
     * @return CustomerBookInterface|null
     */
    public function customerBook(?CustomerBookInterface $customerBook = null): ?CustomerBookInterface;

    /**
     * @return bool
     */
    public function isTaken(): bool;

}
