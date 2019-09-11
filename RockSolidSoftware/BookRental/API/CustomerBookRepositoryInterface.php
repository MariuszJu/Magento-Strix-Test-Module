<?php

namespace RockSolidSoftware\BookRental\API;

use Magento\Framework\DataObject;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;

interface CustomerBookRepositoryInterface extends RepositoryInterface
{

    /**
     * @param int $bookId
     * @return CustomerBookInterface
     */
    public function getByBookId(int $bookId): CustomerBookInterface;

    /**
     * @param int $customerId
     * @return DataObject[]
     */
    public function getByCustomerId(int $customerId): array;

}
