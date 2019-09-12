<?php

namespace RockSolidSoftware\BookRental\API;

use Magento\Framework\DataObject;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;

interface CustomerBookRepositoryInterface extends RepositoryInterface
{

    /**
     * Get Customer Book entry by given Book ID
     *
     * @param int $bookId
     * @return CustomerBookInterface
     */
    public function getByBookId(int $bookId): CustomerBookInterface;

    /**
     * Get Customer Book entries by Customer ID
     *
     * @param int       $customerId
     * @param bool|null $onlyRented
     * @return DataObject[]
     */
    public function getByCustomerId(int $customerId, bool $onlyRented = null): array;

}
