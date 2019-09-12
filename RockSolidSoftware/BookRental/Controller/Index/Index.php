<?php

namespace RockSolidSoftware\BookRental\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{

    /**
     * PageFactory to create static page
     *
     * @var PageFactory
     */
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
        return $this->pageFactory->create();
    }

}
