<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Html\Link;

class CustomerLink extends Link
{

    /**
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
