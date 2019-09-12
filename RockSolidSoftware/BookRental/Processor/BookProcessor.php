<?php

namespace RockSolidSoftware\BookRental\Processor;

use RockSolidSoftware\BookRental\Helper\Inflector;
use RockSolidSoftware\BookRental\API\BookRepositoryInterface;

class BookProcessor
{

    /**
     * Book Repository instance
     *
     * @var BookRepositoryInterface
     */
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
     * Create or updated book
     *
     * @throws \RuntimeException
     * @throws \Exception
     * @param array $bookData
     * @return bool
     */
    public function save(array $bookData = []): bool
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        if (isset($bookData['id'])) {
            $bookData['updated_at'] = $now;
        } else {
            $bookData['created_at'] = $now;
        }

        if (empty($title = $bookData['title'] ?? null)) {
            throw new \RuntimeException('Title cannot be empty');
        }
        if (empty($author = $bookData['author'] ?? null)) {
            throw new \RuntimeException('Author cannot be empty');
        }

        $slug = Inflector::createSlug(vsprintf('%s-%s-%s', [
            Inflector::cutText($title, 20, ''),
            Inflector::cutText($author, 20, ''),
            uniqid(),
        ]));

        $bookData['slug'] = $slug;

        return (bool) $this->bookRepository->save($bookData);
    }

    /**
     * Delete book by its ID
     *
     * @throws \RuntimeException
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->bookRepository->deleteById($id);
    }

    /**
     * Check whether Book with given ID exists
     *
     * @throws \RuntimeException if book was not found
     * @param int $id
     */
    public function checkBook(int $id)
    {
        $this->bookRepository->getById($id);
    }

}
