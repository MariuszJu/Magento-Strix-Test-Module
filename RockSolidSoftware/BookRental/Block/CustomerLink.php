<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Html\Link;

class CustomerLink extends Link
{

    /**
     * Render link in customer dropdown menu, it returns pure HTML code
     *
     * @return string
     */
    protected function _toHtml(): string
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }

        return sprintf('<li><a %s>%s</a>',
            $this->getLinkAttributes(), $this->escapeHtml($this->getLabel())
        );
    }

}
