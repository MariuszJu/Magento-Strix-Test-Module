<?php

namespace RockSolidSoftware\BookRental\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use RockSolidSoftware\BookRental\Helper\Acl;
use Magento\Framework\View\Result\PageFactory;
use RockSolidSoftware\BookRental\Processor\BookProcessor;
use RockSolidSoftware\BookRental\Processor\BookProcessorFactory;

class Edit extends Action
{

    /**@var PageFactory */
    private $pageFactory;

    /** @var BookProcessor */
    private $processor;

    /**
     * Edit constructor
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
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Book'));

        try {
            $book = $this->processor->checkBook((int) $this->getRequest()->getParam('id'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(
                $e instanceof \RuntimeException ? $e->getMessage() : __('Unexpected error occured')
            );

            return $this->_redirect('*/*/index');
        }

        if (!empty($post = $this->getRequest()->getParam('book'))) {
            try {
                $this->processor->save($post);
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(
                    $e instanceof \RuntimeException ? $e->getMessage() : __('Unexpected error occured')
                );

                return $this->_redirect('*/*/edit/id/', [
                    'id' => $post['id'],
                ]);
            }

            $this->messageManager->addSuccessMessage('Book has been saved');

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
        return $this->_authorization->isAllowed(Acl::ACL_BOOK_EDIT);
    }

}
