<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class BookBlock extends Template implements IdentityInterface
{

    /** @var BookInterface[] */
    protected $books = [];

    /** @var BooksServiceInterface */
    protected $booksService;

    /** @var CustomerServiceInterface */
    protected $customerService;

    /**
     * BookBlock constructor
     *
     * @param Context                  $context
     * @param BooksServiceInterface    $booksService
     * @param CustomerServiceInterface $customerService
     */
    public function __construct(Context $context, BooksServiceInterface $booksService, CustomerServiceInterface $customerService)
    {
        $this->booksService = $booksService;
        $this->customerService = $customerService;

        parent::__construct($context);
    }

    /**
     * @param string $slug
     * @return BookInterface
     */
    public function getBook(string $slug)
    {
        if ($book = ($this->books[$slug] ?? null)) {
            return $book;
        }

        $book = $this->books[$slug] = $this->booksService->getBook($slug, true);

        return $book;
    }

    /**
     * @return bool
     */
    public function canCustomerRentBook(): bool
    {
        return $this->customerService->canCustomerRentBook();
    }

    /**
     * @return array
     */
    public function getIdentities(): array
    {
        return $this->getBook($this->getRequest()->getParam('slug'))->getIdentities();
    }

}
