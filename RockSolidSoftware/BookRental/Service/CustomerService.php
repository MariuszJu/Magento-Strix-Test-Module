<?php

namespace RockSolidSoftware\BookRental\Service;

use Magento\Customer\Model\Session;
use RockSolidSoftware\BookRental\Helper\Config;
use RockSolidSoftware\BookRental\Helper\ConfigFactory;
use RockSolidSoftware\BookRental\API\CustomerServiceInterface;
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
    public function __construct(CustomerBookRepositoryInterface $customerBookRepository, Session $customerSession, ConfigFactory $configFactory)
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

        $customerBooks = $this->customerBookRepository->getByCustomerId($customerId);
        $maxBooks = $this->config->configKey(Config::CONFIG_MAX_BOOKS);
        
        return count($customerBooks) < $maxBooks;
    }

}
