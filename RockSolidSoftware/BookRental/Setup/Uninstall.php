<?php

namespace RockSolidSoftware\BookRental\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book;

class Uninstall implements UninstallInterface
{

    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
	public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
        $setup->startSetup();
        $setup->getConnection()->dropTable($setup->getTable(Book::table));
        $setup->endSetup();
	}

}
