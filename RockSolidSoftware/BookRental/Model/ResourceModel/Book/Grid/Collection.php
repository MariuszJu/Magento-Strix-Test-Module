<?php

namespace RockSolidSoftware\BookRental\Model\ResourceModel\Book\Grid;

use Psr\Log\LoggerInterface as Logger;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;

class Collection extends SearchResult
{

    /**
     * Collection constructor
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @param EntityFactory $entityFactory
     * @param Logger        $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager  $eventManager
     */
    public function __construct(EntityFactory $entityFactory, Logger $logger, FetchStrategy $fetchStrategy,
                                EventManager $eventManager)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy,
            $eventManager, Book::table, Book::class
        );
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $items = parent::getItems();



        return $items;
    }

}
