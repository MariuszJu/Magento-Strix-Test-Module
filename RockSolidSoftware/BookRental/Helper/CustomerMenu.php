<?php

namespace RockSolidSoftware\BookRental\Helper;

use Magento\Framework\Phrase;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class CustomerMenu extends AbstractHelper
{

    /** @var Session */
    protected $customerSession;

    /**
     * CustomerMenu constructor
     *
     * @param Context $context
     * @param Session $customerSession
     */
    public function __construct(Context $context, Session $customerSession)
    {
        $this->customerSession = $customerSession;

        parent::__construct($context);
    }

    /**
     * Returns label of link to rented books page in customer dropdown menu
     *
     * @return Phrase|null
     */
    public function getCustomerBooksLinkLabel(): ?Phrase
    {
        return $this->customerSession->isLoggedIn() ? __('My Rented Books') : null;
    }

    /**
     * Returns URL of link to rented books page in customer dropdown menu
     *
     * @return string
     */
    public function getCustomerBooksLinkUrl(): string
    {
        return $this->_urlBuilder->getUrl('book_renal/rent/index');
    }

}
