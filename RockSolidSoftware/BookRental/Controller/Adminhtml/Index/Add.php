<?php

namespace RockSolidSoftware\BookRental\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use RockSolidSoftware\BookRental\Helper\Acl;
use RockSolidSoftware\BookRental\Processor\BookProcessor;
use RockSolidSoftware\BookRental\Processor\BookProcessorFactory;

class Add extends Action
{

    /** @var BookProcessor */
    private $processor;

    /**
     * Add constructor
     *
     * @param Context              $context
     * @param BookProcessorFactory $bookProcessorFactory
     */
    public function __construct(Context $context, BookProcessorFactory $bookProcessorFactory)
    {
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

        if (!empty($post = $this->getRequest()->getParam('book'))) {
            try {
                $this->processor->save(null, $post);
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
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed(Acl::ACL_BOOK_ADD);
    }

}
