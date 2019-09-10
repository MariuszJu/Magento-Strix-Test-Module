<?php

namespace RockSolidSoftware\BookRental\Model;

use Magento\Framework\Model\AbstractModel;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book as BookResource;

class Book extends AbstractModel implements BookInterface
{

    /** @var CustomerBookInterface */
    protected $customerBook;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BookResource::class);
    }

    /**
     * @param CustomerBookInterface|null $customerBook
     * @return CustomerBookInterface|null
     */
    public function customerBook(?CustomerBookInterface $customerBook = null): ?CustomerBookInterface
    {
        if (!is_null($customerBook)) {
            $this->customerBook = $customerBook;
        }

        return $this->customerBook;
    }

    /**
     * @return bool
     */
    public function isTaken(): bool
    {
        return !empty($this->customerBook);
    }

}
