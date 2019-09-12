<?php

namespace RockSolidSoftware\BookRental\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book;
use RockSolidSoftware\BookRental\Model\ResourceModel\CustomerBook;

class Uninstall implements UninstallInterface
{

    /**
     * Perform deinstallation
     *
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
	public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
        $setup->startSetup();

        $setup->getConnection()->dropTable($setup->getTable(Book::table));
        $setup->getConnection()->dropTable($setup->getTable(CustomerBook::table));

        $setup->endSetup();
	}

}
