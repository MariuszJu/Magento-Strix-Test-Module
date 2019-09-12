<?php

namespace RockSolidSoftware\BookRental\Block\Adminhtml\Form\Book;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends BaseButton implements ButtonProviderInterface
{

    /**
     * Get Back button configuration
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label'      => __('Go Back'),
            'on_click'   => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class'      => 'back',
            'sort_order' => 10,
        ];
    }

    /**
     * Get Back button URL
     *
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getUrl('*/*/');
    }

}
