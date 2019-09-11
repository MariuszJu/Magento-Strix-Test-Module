<?php

namespace RockSolidSoftware\BookRental\Controller\Index;

use Magento\Framework\UrlInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class Rent extends Action
{

    /**@var PageFactory */
    private $pageFactory;

    /** @var CustomerServiceInterface */
    private $customerService;

    /** @var UrlInterface */
    private $url;

    /**
     * Rent constructor
     *
     * @param Context                  $context
     * @param PageFactory              $pageFactory
     * @param CustomerServiceInterface $customerService
     * @param UrlInterface             $url
     */
    public function __construct(Context $context, PageFactory $pageFactory, CustomerServiceInterface $customerService,
                                UrlInterface $url)
    {
        $this->url = $url;
        $this->pageFactory = $pageFactory;
        $this->customerService = $customerService;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            if (empty($this->getRequest()->getParam('slug'))) {
                throw new \RuntimeException(__('Invalid request'));
            }

            $this->customerService->authenticateCustomer($this->url->getCurrentUrl());
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(
                $e instanceof \RuntimeException
                    ? $e->getMessage() : __('Unexpected error occured. Please try again')
            );
            
            return $this->_redirect('*/*/index');
        }

        return $this->pageFactory->create();
    }

}
