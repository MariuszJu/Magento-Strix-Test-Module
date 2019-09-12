<?php

namespace RockSolidSoftware\BookRental\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use RockSolidSoftware\BookRental\Helper\Acl;
use Magento\Framework\View\Result\PageFactory;
use RockSolidSoftware\BookRental\Processor\BookProcessor;
use RockSolidSoftware\BookRental\Processor\BookProcessorFactory;

class Add extends Action
{

    /**@var PageFactory */
    private $pageFactory;

    /** @var BookProcessor */
    private $processor;

    /**
     * Add constructor
     *
     * @param Context              $context
     * @param BookProcessorFactory $bookProcessorFactory
     * @param PageFactory          $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory, BookProcessorFactory $bookProcessorFactory)
    {
        $this->pageFactory = $pageFactory;
        $this->processor = $bookProcessorFactory->create();

        parent::__construct($context);
    }

    /**
     * @throws \RuntimeException
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();

        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Add New Book'));

        if (!empty($post = $this->getRequest()->getParam('book'))) {
            try {
                $this->processor->save($post);
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(
                    $e instanceof \RuntimeException ? $e->getMessage() : 'Unexpected error occured'
                );

                return $this->_redirect('*/*/add');
            }

            $this->messageManager->addSuccessMessage('Book has been created');

            return $this->_redirect('*/*/index');
        }
    }

    /**
     * Check whether admin has appropriate permissions to access this action
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed(Acl::ACL_BOOK_ADD);
    }

}
