<?php

namespace RockSolidSoftware\BookRental\Service;

use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;
use RockSolidSoftware\BookRental\Helper\Config;
use RockSolidSoftware\BookRental\Helper\ConfigFactory;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;
use RockSolidSoftware\BookRental\API\CustomerBookRepositoryInterface;

class CustomerService implements CustomerServiceInterface
{

    /**
     * Customer Book Repository instance
     *
     * @var CustomerBookRepositoryInterface
     */
    private $customerBookRepository;

    /**
     * Customer Session Factory instance
     *
     * @var SessionFactory
     */
    private $customerSession;

    /**
     * Config helper instance
     *
     * @var Config
     */
    private $config;

    /**
     * CustomerService constructor
     *
     * @param CustomerBookRepositoryInterface $customerBookRepository
     * @param SessionFactory                  $customerSession
     * @param ConfigFactory                   $configFactory
     */
    public function __construct(CustomerBookRepositoryInterface $customerBookRepository, SessionFactory $customerSession,
                                ConfigFactory $configFactory)
    {
        $this->config = $configFactory->create();
        $this->customerSession = $customerSession;
        $this->customerBookRepository = $customerBookRepository;
    }

    /**
     * Check whether user is authenticated already - if is not redirect him to given URL
     *
     * @param string|null $afterAuthUrl
     */
    public function authenticateCustomer(string $afterAuthUrl = null)
    {
        if (!is_null($afterAuthUrl)) {
            $this->customerSession()->setAfterAuthUrl($afterAuthUrl);
        }

        $this->customerSession()->authenticate();
    }

    /**
     * Check whether customer is authenticated
     *
     * @return bool
     */
    public function isCustomerLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * Get customer ID (if it is authenticated)
     *
     * @return int|null
     */
    public function customerId(): ?int
    {
        return $this->customerSession()->getCustomerId();
    }

    /**
     * Check whether given customer can rent a book
     *
     * @throws \RuntimeException if customer is not authenticated
     * @param int|null $customerId
     * @return bool
     */
    public function canCustomerRentBook(int $customerId = null): bool
    {
        if (empty($customerId)) {
            $customerId = $this->customerId();
        }

        if (empty($customerId)) {
            throw new \RuntimeException(__('No customer is logged in'));
        }

        $customerBooks = $this->customerBookRepository->getByCustomerId($customerId, true);
        $maxBooks = $this->config->configKey(Config::CONFIG_MAX_BOOKS);
        
        return count($customerBooks) < $maxBooks;
    }

    /**
     * Rent a book by customer
     *
     * @throws \RuntimeException if customer is not authenticated or can't rent a book (reached limit)
     * @param BookInterface $book
     * @param int|null      $customerId
     */
    public function rentBook(BookInterface $book, int $customerId = null)
    {
        $customerId = empty($customerId) ? $this->customerId() : $customerId;

        if (!$this->canCustomerRentBook($customerId)) {
            throw new \RuntimeException(
                __('You reached out your rented books limit. Please return one of your books to be abile to rent another')
            );
        }
        if ($book->isTaken()) {
            if ($book->customerBook()->getCustomerId() == $customerId) {
                throw new \RuntimeException(__('You already own this book'));
            }

            throw new \RuntimeException(__('This book is already rented'));
        }

        $this->customerBookRepository->save([
            'customer_id' => $customerId,
            'book_id'     => $book->getId(),
            'is_rented'   => 1,
            'created_at'  => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @throws \RuntimeException if customer is not authenticated or does not own given book
     * @param BookInterface $book
     * @param int|null      $customerId
     */
    public function returnBook(BookInterface $book, int $customerId = null)
    {
        $customerId = empty($customerId) ? $this->customerId() : $customerId;

        if (!$book->isTaken()) {
            throw new \RuntimeException(__('Cannot return this book'));
        }

        $customerBook = $book->customerBook();

        if ($customerBook->getCustomerId() != $customerId) {
            throw new \RuntimeException(__('You do not own such book'));
        }

        $customerBook->setIsRented(0);
        $customerBook->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));

        $this->customerBookRepository->save($customerBook);
    }

    /**
     * Get rented book for given customer
     *
     * @throws \RuntimeException if customer is not authenticated
     * @param int|null $customerId
     * @return CustomerBookInterface[]
     */
    public function customerBooks(int $customerId = null): array
    {
        $customerId = empty($customerId) ? $this->customerId() : $customerId;

        if (empty($customerId)) {
            throw new \RuntimeException(__('No customer is logged in'));
        }

        return $this->customerBookRepository->getByCustomerId($customerId, true);
    }

    /**
     * Get customer session instance
     *
     * @return Session
     */
    private function customerSession(): Session
    {
        return $this->customerSession->create();
    }

}
