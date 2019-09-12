<?php

namespace RockSolidSoftware\BookRental\Plugin;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context;
use Magento\Customer\Model\SessionFactory;

class CustomerCacheContextPlugin
{

    const CONTEXT_CUSTOMER_ID_NAME = 'CONTEXT_CUSTOMER_ID';
    const NOT_LOGGED_IN_CUSTOMER_ID = null;

    /** @var SessionFactory */
    protected $customerSession;

    /**
     * CustomerCacheContextPlugin constructor
     *
     * @param SessionFactory $customerSession
     */
    public function __construct(SessionFactory $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    /**
     * @return Session
     */
    private function customerSession(): Session
    {
        return $this->customerSession->create();
    }

    /**
     * Set custom context variable to distinguish cached pages for logged in customers
     *
     * @param Context $context
     */
    public function beforeGetVaryString(Context $context)
    {
        if (!$this->customerSession()->isLoggedIn()) {
            return;
        }

        $context->setValue(
            self::CONTEXT_CUSTOMER_ID_NAME,
            $this->customerSession()->getCustomerId(),
            self::NOT_LOGGED_IN_CUSTOMER_ID
        );
    }

}
