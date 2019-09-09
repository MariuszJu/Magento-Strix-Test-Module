<?php

namespace RockSolidSoftware\BookRental\Model;

use Magento\Framework\Model\AbstractModel;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook as CustomerBookResource;

class CustomerBook extends AbstractModel implements CustomerBookInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CustomerBookResource::class);
    }

}
