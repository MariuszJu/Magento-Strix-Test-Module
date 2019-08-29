<?php

namespace RockSolidSoftware\BookRental\Model\DataProvider;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use RockSolidSoftware\BookRental\API\BookRepositoryInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\Collection;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\CollectionFactory;

class Book extends AbstractDataProvider
{

    /** @var BookRepositoryInterface */
    protected $repository;

    /** @var RequestInterface */
    protected $request;

    /**
     * Book constructor
     *
     * @param                         $name
     * @param                         $primaryFieldName
     * @param                         $requestFieldName
     * @param CollectionFactory       $collectionFactory
     * @param BookRepositoryInterface $repository
     * @param array                   $meta
     * @param array                   $data
     * @param RequestInterface        $request
     */
    public function __construct($name, $primaryFieldName, $requestFieldName,
                                CollectionFactory $collectionFactory, BookRepositoryInterface $repository,
                                array $meta = [], array $data = [], RequestInterface $request)
    {
        $this->request = $request;
        $this->repository = $repository;
        $this->collection = $collectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [];

        $id = $this->request->getParam('id');

        if (!empty($post = $this->request->getPostValue())) {
            $data[$id]['book'] = $post['book'] ?? [];

            return $data;
        }

        if ($id = $this->request->getParam('id')) {
            try {
                $data[$id]['book'] = $this->repository
                    ->getById($id)
                    ->getData();
            } catch (\Throwable $e) {

            }
        }

        return $data;

    }

}
