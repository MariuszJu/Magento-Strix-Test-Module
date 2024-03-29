<?php

namespace RockSolidSoftware\BookRental\Model\DataProvider;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use RockSolidSoftware\BookRental\API\BookRepositoryInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book\CollectionFactory;

class Book extends AbstractDataProvider
{

    /**
     * Book Repository instance
     *
     * @var BookRepositoryInterface
     */
    protected $repository;

    /**
     * Request instance to get ID from URL
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Book constructor
     *
     * @param string                  $name
     * @param string                  $primaryFieldName
     * @param string                  $requestFieldName
     * @param CollectionFactory       $collectionFactory
     * @param BookRepositoryInterface $repository
     * @param array                   $meta
     * @param array                   $data
     * @param RequestInterface        $request
     */
    public function __construct(string $name, string $primaryFieldName, string $requestFieldName,
                                CollectionFactory $collectionFactory, BookRepositoryInterface $repository,
                                array $meta = [], array $data = [], RequestInterface $request)
    {
        $this->request = $request;
        $this->repository = $repository;
        $this->collection = $collectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Provide data for Admin area create/edit book form
     *
     * @return array
     */
    public function getData()
    {
        $data = [];

        $id = (int) $this->request->getParam('id');

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
