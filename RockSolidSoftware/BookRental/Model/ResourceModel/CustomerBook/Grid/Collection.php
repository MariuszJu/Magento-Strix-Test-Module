<?php

namespace RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook\Grid;

use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\App\RequestInterface;
use RockSolidSoftware\BookRental\API\BooksServiceInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;

class Collection extends SearchResult
{

    /**
     * Books Service instance
     *
     * @var BooksServiceInterface
     */
    private $booksService;

    /**
     * Request instance to get ID from URL
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Collection constructor
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @param EntityFactory         $entityFactory
     * @param Logger                $logger
     * @param FetchStrategy         $fetchStrategy
     * @param EventManager          $eventManager
     * @param BooksServiceInterface $booksService
     * @param RequestInterface      $request
     */
    public function __construct(EntityFactory $entityFactory, Logger $logger, FetchStrategy $fetchStrategy,
                                EventManager $eventManager, BooksServiceInterface $booksService,
                                RequestInterface $request)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy,
            $eventManager, CustomerBook::table, CustomerBook::class
        );

        $this->request = $request;
        $this->booksService = $booksService;
        
        if (!empty($id = $this->request->getParam('id'))) {
            $_SESSION['book_history_listing_book_id'] = $id;
        }
    }

    /**
     * Get data for book history in Admin area grid
     *
     * @return array
     */
    public function getItems(): array
    {
        $this->getSelect()
            ->joinLeft(
                ['customer' => 'customer_entity'],
                'customer.entity_id = main_table.customer_id', [
                    'customer_email'     => 'customer.email',
                    'customer_firstname' => 'customer.firstname',
                    'customer_lastname'  => 'customer.lastname',
                ]
            );

        $bookId = $_SESSION['book_history_listing_book_id'] ?? $this->request->getParam('id');

        $this->addFilter('book_id', $bookId);

        $items = parent::getItems();

        foreach ($items as $item) {
            try {
                $data = $item->getData();

                $data['rented'] = !empty($rented = $data['created_at'])
                    ? (new \DateTime($rented))->format('Y-m-d H:i') : '';
                $data['returned'] = !empty($returned = $data['updated_at'])
                    ? (new \DateTime($returned))->format('Y-m-d H:i') : '';
                $data['customer'] = vsprintf('#%s %s %s', [
                    $data['customer_id'], $data['customer_firstname'], $data['customer_lastname']
                ]);

                $item->setData($data);
            } catch (\Throwable $e) {

            }
        }

        return $items;
    }

}
