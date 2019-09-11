<?php

namespace RockSolidSoftware\BookRental\Controller\Rent;

use Magento\Framework\UrlInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class ReturnBook extends Action
{

    /** @var CustomerServiceInterface */
    private $customerService;

    /** @var BooksServiceInterface */
    private $booksService;

    /** @var PageFactory */
    private $pageFactory;

    /** @var UrlInterface */
    private $url;

    /**
     * ReturnBook constructor
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
            $this->customerService->returnBook($book);

            $this->messageManager->addSuccessMessage(__('Book has been returned'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(
                $e instanceof \RuntimeException
                    ? $e->getMessage() : __('Unexpected error occured. Please try again')
            );
            
            echo '<pre>';
            print_r($e->getMessage());
            echo '</pre>'; die('');

            return $this->_redirect('*/*/index');
        }

        return $this->_redirect('*/*/index');
    }

}
