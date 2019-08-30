<?php

namespace RockSolidSoftware\BookRental\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;

class FrontentBlock extends Template
{

    /** @var array */
    protected $defaultOrder = ['id' => 'DESC'];

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
     * @param int $page
     * @return array
     */
    public function getBooks(int $page = 1): array
    {
        $config = $this->service->getBooksPagination($page, $this->perPage, $this->defaultOrder);
        
        echo '<pre>';
        var_dump($config);
        echo '</pre>'; die('tt');
    }

}
