<?php

namespace RockSolidSoftware\BookRental\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class BookActions extends Column
{

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory,
                                UrlInterface $urlBuilder, array $components = [], array $data = [])
    {
        $this->urlBuilder = $urlBuilder;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source for Edit button in Admin grid
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');

            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href'   => $this->urlBuilder->getUrl(
                        'book_rental_list/index/edit',
                        ['id' => $item['id'], 'store' => $storeId]
                    ),
                    'label'  => __('Edit'),
                    'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }
}
