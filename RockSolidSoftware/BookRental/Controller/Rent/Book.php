<?php

namespace RockSolidSoftware\BookRental\Controller\Rent;

use Magento\Framework\UrlInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class Book extends Action
{

    /**
     * PageFactory to create static page
     *
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Customer Service instance
     *
     * @var CustomerServiceInterface
     */
    private $customerService;

    /**
     * Books Service instance
     *
     * @var BooksServiceInterface
     */
    private $booksService;

    /**
     * URL interface to generate URL's
     *
     * @var UrlInterface
     */
    private $url;

    /**
     * Book constructor
     *
     * @param Context                  $context
     * @param PageFactory              $pageFactory
     * @param BooksServiceInterface    $booksService
     * @param CustomerServiceInterface $customerService
     * @param UrlInterface             $url
     */
    public function __construct(Context $context, PageFactory $pageFactory, BooksServiceInterface $booksService,
                                CustomerServiceInterface $customerService, UrlInterface $url)
    {
        $this->url = $url;
        $this->pageFactory = $pageFactory;
        $this->booksService = $booksService;
        $this->customerService = $customerService;

        parent::__construct($context);
    }

    /**
     * Check whether customer is authenticated and check whether customer owns indicated book
     * 
     * @return mixed
     */
    public function execute()
    {
        try {
            if (empty($slug = $this->getRequest()->getParam('slug'))) {
                throw new \RuntimeException(__('Invalid request'));
            }

            $this->customerService->authenticateCustomer($this->url->getCurrentUrl());

            $book = $this->booksService->getBook($slug, true);

            if (!$book->isTaken() || $book->customerBook()->getCustomerId() != $this->customerService->customerId()) {
                throw new \RuntimeException(__('You do not own such book'));
            }
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
