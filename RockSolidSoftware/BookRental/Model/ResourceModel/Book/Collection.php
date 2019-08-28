<?php

namespace RockSolidSoftware\BookRental\Model\ResourceModel\Book;

use RockSolidSoftware\BookRental\Model\Book;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book as BookResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'rocksolidsoftware_bool_rental_collection';
    protected $_eventObject = 'book_collection';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Book::class, BookResource::class);
    }

}
