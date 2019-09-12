<?php

namespace RockSolidSoftware\BookRental\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook as CustomerBookResource;

class CustomerBook extends AbstractModel implements CustomerBookInterface, IdentityInterface
{

    const CACHE_TAG = 'customer_book';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CustomerBookResource::class);
    }

    /**
     * Get cache tags to refresh cached pages if necessary
     *
     * @return array
     */
    public function getIdentities(): array
    {
        $identities = [
            self::CACHE_TAG . '_' . $this->getId(),
        ];

        if ($this->hasDataChanges() || $this->isDeleted()) {
            $identities[] = Book::CACHE_TAG . '_' . $this->getBookId();
        }

        return $identities;
    }

}
