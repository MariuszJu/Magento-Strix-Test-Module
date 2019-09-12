<?php

namespace RockSolidSoftware\BookRental\Controller\Adminhtml\Index;

//use Magento\Framework\App\Action\Action;
use Magento\Backend\App\Action;
//use Magento\Framework\App\Action\Context;
use Magento\Backend\App\Action\Context;
use RockSolidSoftware\BookRental\Helper\Acl;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{

    /**@var PageFactory */
    private $pageFactory;

    /**
     * Index constructor
     *
     * @param Context     $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Books List'));
        
        return $resultPage;
    }

    /**
     * Check whether admin has appropriate permissions to access this action
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed(Acl::ACL_BOOKS_LIST);
    }

}
