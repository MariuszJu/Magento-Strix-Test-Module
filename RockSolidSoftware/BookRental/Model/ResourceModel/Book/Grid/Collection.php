<?php

namespace RockSolidSoftware\BookRental\Model\ResourceModel\Book\Grid;

use Psr\Log\LoggerInterface as Logger;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;

class Collection extends SearchResult
{

    /** @var BooksServiceInterface */
    private $booksService;

    /**
     * Collection constructor
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @param EntityFactory         $entityFactory
     * @param Logger                $logger
     * @param FetchStrategy         $fetchStrategy
     * @param EventManager          $eventManager
     * @param BooksServiceInterface $booksService
     */
    public function __construct(EntityFactory $entityFactory, Logger $logger, FetchStrategy $fetchStrategy,
                                EventManager $eventManager, BooksServiceInterface $booksService)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy,
            $eventManager, Book::table, Book::class
        );

        $this->booksService = $booksService;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            $data = $item->getData();

            $book = $this->booksService->getBook($data['id'], true);

            if ($book->isTaken()) {
                $customerBook = $book->customerBook();

                $data['status'] = sprintf(
                    '%s #%s %s %s',
                    __('Rented by'), $customerBook->getCustomerId(), $customerBook->getCustomerFirstname(),
                    $customerBook->getCustomerLastname()
                );
            } else {
                $data['status'] = __('Available');
            }

            $item->setData($data);
        }

        return $items;
    }

}
