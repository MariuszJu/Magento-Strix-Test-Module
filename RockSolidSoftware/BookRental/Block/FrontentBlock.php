<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;

class FrontentBlock extends Template
{

    /** @var array */
    protected $defaultOrder = ['main_table.id' => 'DESC'];

    /** @var int */
    protected $perPage = 10;

    /** @var BooksServiceInterface */
    protected $service;

    /**
     * FrontentBlock constructor
     *
     * @param Context               $context
     * @param BooksServiceInterface $booksService
     */
    public function __construct(Context $context, BooksServiceInterface $booksService)
    {
        $this->service = $booksService;

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
            $count = $this->service->getBooksCount();

            $pages = ceil($count / ($perPage = ($perPage ?? $this->perPage)));

            if ($page > $pages) {
                $page = $pages;
            }

            $books = $this->service->getBooks($page, $perPage, $order ?? $this->defaultOrder);
        } catch (\Throwable $e) {
            var_dump($e->getMessage()); die;
        }

        return [
            'page'  => $page,
            'pages' => $pages,
            'books' => $books,
        ];
    }

}
