<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class BooksBlock extends Template implements IdentityInterface
{

    /**
     * Cached books, to prevent retrieving them multiple times
     *
     * @var array
     */
    protected $books = [];

    /**
     * Default order for books list
     *
     * @var array
     */
    protected $defaultOrder = ['main_table.id' => 'DESC'];

    /**
     * How many books will be shown per page
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * Books Service instance
     *
     * @var BooksServiceInterface
     */
    protected $booksService;

    /**
     * Customer Service instance
     *
     * @var CustomerServiceInterface
     */
    protected $customerService;

    /**
     * FrontentBlock constructor
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
     * Get books list with pagination
     *
     * @param int        $page
     * @param int|null   $perPage
     * @param array|null $order
     * @return array
     */
    public function getBooks(int $page = 1, int $perPage = null, array $order = null): array
    {
        if (!empty($this->books)) {
            return $this->books;
        }

        $count = $this->booksService->getBooksCount();

        $pages = ceil($count / ($perPage = ($perPage ?? $this->perPage)));

        if ($page > $pages) {
            $page = $pages;
        }

        $books = $this->booksService->getBooks($page, $perPage, $order ?? $this->defaultOrder);

        $this->books = [
            'page'  => $page,
            'pages' => $pages,
            'books' => $books,
        ];

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

        foreach ($this->getBooks($this->getRequest()->getParam('page', 1))['books'] as $book) {
            $identities = array_merge($book->getIdentities(), $identities);

            if ($book->isTaken()) {
                $identities = array_merge($book->customerBook()->getIdentities(), $identities);
            }
        }
        
        return $identities;
    }

}
