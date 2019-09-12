<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class CustomerBooksBlock extends Template implements IdentityInterface
{

    /** @var array */
    protected $books = [];

    /** @var CustomerServiceInterface */
    protected $customerService;

    /**
     * CustomerBooksBlock constructor
     *
     * @param Context                  $context
     * @param CustomerServiceInterface $customerService
     */
    public function __construct(Context $context, CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function customerBooks()
    {
        if (!empty($this->books)) {
            //return $this->books;
        }

        $this->books = $this->customerService->customerBooks();

        return $this->books;
    }

    /**
     * @return array
     */
    public function getIdentities(): array
    {
        $identities = [];

        foreach ($this->customerBooks() as $customerBook) {
            $identities = array_merge($customerBook->getIdentities(), $identities);
        }
        
        return $identities;
    }

}
