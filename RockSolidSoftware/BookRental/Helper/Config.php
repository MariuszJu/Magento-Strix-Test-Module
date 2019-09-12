<?php

namespace RockSolidSoftware\BookRental\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Tests\NamingConvention\true\mixed;

class Config extends AbstractHelper
{

    const CONFIG_MAX_BOOKS = 'book_rental/book_rental_general/book_rental_max_books';

    /**
     * Get value of configuration item from Admin area
     *
     * @param string      $key
     * @param string|null $storeId
     * @param mixed       $default
     * @return mixed
     */
    public function configKey(string $key, $default = null, string $storeId = null)
    {
        $value = $this->scopeConfig->getValue($key, ScopeInterface::SCOPE_STORE, $storeId);

        if (is_null($value)) {
            return is_callable($default) ? call_user_func($default) : $default;
        }

        return $value;
    }

}
