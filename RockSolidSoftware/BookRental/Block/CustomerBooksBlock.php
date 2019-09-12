<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class CustomerBooksBlock extends Template implements IdentityInterface
{

    /**
     * Cached books, to prevent retrieving them multiple times
     *
     * @var array
     */
    protected $books = [];

    /**
     * Customer Service instance
     *
     * @var CustomerServiceInterface
     */
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
     * Get rented books of currently logged in customer
     *
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
     * Get cache tags to refresh page if necessary
     *
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
