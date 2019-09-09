<?php

namespace RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook;

use RockSolidSoftware\BookRental\Model\CustomerBook;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook as CustomerBookResource;

class Collection extends AbstractCollection
{

    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'rocksolidsoftware_bool_rental_customer_collection';
    protected $_eventObject = 'customer_book_collection';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CustomerBook::class, CustomerBookResource::class);
    }

}
