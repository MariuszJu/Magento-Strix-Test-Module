<?php

namespace RockSolidSoftware\BookRental\Service;

use Magento\Customer\Model\Session;
use RockSolidSoftware\BookRental\Helper\Config;
use RockSolidSoftware\BookRental\Helper\ConfigFactory;
use RockSolidSoftware\BookRental\API\Data\BookInterface;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;
use RockSolidSoftware\BookRental\API\Data\CustomerBookInterface;
use RockSolidSoftware\BookRental\API\CustomerBookRepositoryInterface;

class CustomerService implements CustomerServiceInterface
{

    /** @var CustomerBookRepositoryInterface */
    private $customerBookRepository;

    /** @var Session */
    private $customerSession;

    /** @var Config */
    private $config;

    /**
     * CustomerService constructor.
     *
     * @param CustomerBookRepositoryInterface $customerBookRepository
     * @param Session                         $customerSession
     * @param ConfigFactory                   $configFactory
     */
    public function __construct(CustomerBookRepositoryInterface $customerBookRepository, Session $customerSession,
                                ConfigFactory $configFactory)
    {
        $this->config = $configFactory->create();
        $this->customerSession = $customerSession;
        $this->customerBookRepository = $customerBookRepository;
    }

    /**
     * @param string|null $afterAuthUrl
     */
    public function authenticateCustomer(string $afterAuthUrl = null)
    {
        if (!is_null($afterAuthUrl)) {
            $this->customerSession->setAfterAuthUrl($afterAuthUrl);
        }

        $this->customerSession->authenticate();
    }

    /**
     * @return bool
     */
    public function isCustomerLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @return int|null
     */
    public function customerId(): ?int
    {
        return $this->customerSession->getCustomerId();
    }

    /**
     * @throws \RuntimeException
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
     * @throws \RuntimeException
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
     * @throws \RuntimeException
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

        $this->customerBookRepository->save($customerBook);
    }

    /**
     * @param int|null $customerId
     * @return CustomerBookInterface[]
     */
    public function customerBooks(int $customerId = null): array
    {
        $customerId = empty($customerId) ? $this->customerId() : $customerId;

        return $this->customerBookRepository->getByCustomerId($customerId, true);
    }

}
