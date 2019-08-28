<?php

namespace RockSolidSoftware\BookRental\Processor;

use RockSolidSoftware\BookRental\Model\Book;
use RockSolidSoftware\BookRental\API\BookRepositoryInterface;

class BookProcessor
{

    /** @var BookRepositoryInterface */
    private $bookRepository;

    /**
     * BookProcessor constructor
     *
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @throws \RuntimeException
     * @throws \Exception
     * @param int|null $id
     * @param array    $post
     * @return bool
     */
    public function save(?int $id = null, array $post = []): bool
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        if (!empty($id)) {
            /** @var Book $book */
            $book = $this->bookRepository->getById($id);
            $book->setData('updated_at', $now);

            $bookData = $book->getData();
        } else {
            $book = $bookData = $post;
            $book['created_at'] = $now;
        }

        if (empty($bookData['title'] ?? null)) {
            throw new \RuntimeException('Title cannot be empty');
        }
        if (empty($bookData['author'] ?? null)) {
            throw new \RuntimeException('Author cannot be empty');
        }

        return (bool) $this->bookRepository->save($book);
    }

    /**
     * @throws \RuntimeException
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->bookRepository->deleteById($id);
    }

}
