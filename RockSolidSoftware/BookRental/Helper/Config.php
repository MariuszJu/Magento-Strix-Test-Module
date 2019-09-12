<?php

namespace RockSolidSoftware\BookRental\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{

    const CONFIG_MAX_BOOKS = 'book_rental/book_rental_general/book_rental_max_books';

    /**
     * Get value of configuration item from Admin area
     *
     * @param string $key
     * @param null   $storeId
     * @return mixed
     */
    public function configKey(string $key, $storeId = null)
    {
        return $this->scopeConfig->getValue($key, ScopeInterface::SCOPE_STORE, $storeId);
    }

}
