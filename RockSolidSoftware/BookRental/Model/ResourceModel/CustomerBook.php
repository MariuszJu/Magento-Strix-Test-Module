<?php

namespace RockSolidSoftware\BookRental\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerBook extends AbstractDb
{

    const table = 'book_rental_customer_books';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::table, 'id');
    }

}
