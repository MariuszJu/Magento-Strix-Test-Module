<?php

namespace RockSolidSoftware\BookRental\Block\Adminhtml\Form\Book;

use Magento\Backend\Block\Widget\Context;

class BaseButton
{

    /** @var \Magento\Framework\UrlInterface */
    protected $urlBuilder;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $registry
     */
    public function __construct(Context $context)
    {
        $this->urlBuilder = $context->getUrlBuilder();
    }

    /**
     * @param string $route
     * @param array  $params
     * @return string
     */
    public function getUrl($route = '', $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

}

