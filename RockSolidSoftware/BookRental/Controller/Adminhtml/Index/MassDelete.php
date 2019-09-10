<?php

namespace RockSolidSoftware\BookRental\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use RockSolidSoftware\BookRental\Helper\Acl;
use RockSolidSoftware\BookRental\Processor\BookProcessor;
use RockSolidSoftware\BookRental\Processor\BookProcessorFactory;

class MassDelete extends Action
{

    /** @var BookProcessor */
    private $processor;

    /**
     * MassDelete constructor
     *
     * @param Context              $context
     * @param BookProcessorFactory $pageFactory
     */
    public function __construct(Context $context, BookProcessorFactory $bookProcessorFactory)
    {
        $this->processor = $bookProcessorFactory->create();

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $errors = 0;
        $bookIds = $this->getRequest()->getPostValue()['selected'] ?? [];

        foreach ($bookIds as $id) {
            try {
                $this->processor->delete($id);
            } catch (\Throwable $e) {
                $errors++;
            }
        }

        if ($errors === 0) {
            $this->messageManager->addSuccessMessage('Selected items have been deleted');
        } else {
            $this->messageManager->addWarningMessage('There was some issues during deleting selected items');
        }

        return $this->_redirect('*/*/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed(Acl::ACL_BOOK_DELETE);
    }

}
