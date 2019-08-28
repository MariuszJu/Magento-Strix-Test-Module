<?php

namespace RockSolidSoftware\BookRental\Model\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use RockSolidSoftware\BookRental\Model\Book as BookModel;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\Collection;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\CollectionFactory;

class Book extends AbstractDataProvider
{

    /** @var Collection */
    protected $collection;

    /**
     * Book constructor
     *
     * @param                   $name
     * @param                   $primaryFieldName
     * @param                   $requestFieldName
     * @param CollectionFactory $contactCollectionFactory
     * @param array             $meta
     * @param array             $data
     */
    public function __construct($name, $primaryFieldName, $requestFieldName,
                                CollectionFactory $contactCollectionFactory, array $meta = [], array $data = [])
    {
        $this->collection = $contactCollectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if ($data = ($this->loadedData ?? [])) {
            return $data;
        }

        $items = $this->collection->getItems();
        $this->loadedData = [];

        /** @var BookModel $book */
        foreach ($items as $book) {
            $this->loadedData[$book->getId()]['book'] = $book->getData();
        }

        return $this->loadedData;

    }

}
