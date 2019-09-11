<?php

namespace RockSolidSoftware\BookRental\Controller\Rent;

use Magento\Framework\UrlInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class Rent extends Action
{

    /** @var CustomerServiceInterface */
    private $customerService;

    /** @var BooksServiceInterface */
    private $booksService;

    /** @var UrlInterface */
    private $url;

    /**
     * Rent constructor
     *
     * @param Context                  $context
     * @param BooksServiceInterface    $booksService
     * @param CustomerServiceInterface $customerService
     * @param UrlInterface             $url
     */
    public function __construct(Context $context, BooksServiceInterface $booksService,
                                CustomerServiceInterface $customerService, UrlInterface $url)
    {
        $this->url = $url;
        $this->booksService = $booksService;
        $this->customerService = $customerService;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            $this->customerService->authenticateCustomer($this->url->getCurrentUrl());

            if (empty($slug = $this->getRequest()->getParam('slug'))) {
                throw new \RuntimeException(__('Invalid request'));
            }

            $book = $this->booksService->getBook($slug, true);
            $this->customerService->rentBook($book);

            $this->messageManager->addSuccessMessage(__('Book has been rented'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(
                $e instanceof \RuntimeException
                    ? $e->getMessage() : __('Unexpected error occured. Please try again')
            );

            return $this->_redirect('*/index/index');
        }

        return $this->_redirect('*/*/index');
    }

}
