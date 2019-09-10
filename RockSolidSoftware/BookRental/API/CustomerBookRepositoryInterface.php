<?php

namespace RockSolidSoftware\BookRental\API;

use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;

interface CustomerBookRepositoryInterface extends RepositoryInterface
{

    /**
     * @param int $bookId
     * @return CustomerBookInterface
     */
    public function getByBookId(int $bookId): CustomerBookInterface;

}
