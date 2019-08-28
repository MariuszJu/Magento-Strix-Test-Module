<?php

namespace RockSolidSoftware\BookRental\Model;

use Magento\Framework\Model\AbstractModel;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book as BookResource;

class Book extends AbstractModel implements BookInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BookResource::class);
    }

}
