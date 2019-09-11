<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;

class FrontentBlock extends Template
{

    /** @var array */
    protected $defaultOrder = ['main_table.id' => 'DESC'];

    /** @var int */
    protected $perPage = 10;

    /** @var BooksServiceInterface */
    protected $booksService;

    /** @var CustomerServiceInterface */
    private $customerService;

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
     * @param int        $page
     * @param int|null   $perPage
     * @param array|null $order
     * @return array
     */
    public function getBooks(int $page = 1, int $perPage = null, array $order = null): array
    {
        try {
            $count = $this->booksService->getBooksCount();

            $pages = ceil($count / ($perPage = ($perPage ?? $this->perPage)));

            if ($page > $pages) {
                $page = $pages;
            }

            $books = $this->booksService->getBooks($page, $perPage, $order ?? $this->defaultOrder);
        } catch (\Throwable $e) {
            var_dump($e->getMessage()); die;
        }

        return [
            'page'  => $page,
            'pages' => $pages,
            'books' => $books,
        ];
    }

    /**
     * @param string $slug
     * @return BookInterface
     */
    public function getBook(string $slug)
    {
        return $this->booksService->getBook($slug, true);
    }

    /**
     * @return bool
     */
    public function canCustomerRentBook(): bool
    {
        return $this->customerService->canCustomerRentBook();
    }

}
