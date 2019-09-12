<?php

namespace RockSolidSoftware\BookRental\Block\Adminhtml\Form\Book;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends BaseButton implements ButtonProviderInterface
{

    /**
     * Get Save button configuration
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label'          => __('Save'),
            'class'          => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'save',
                    ],
                ],
                'form-role' => 'save',
            ],
            'sort_order'     => 90,
        ];
    }

}
