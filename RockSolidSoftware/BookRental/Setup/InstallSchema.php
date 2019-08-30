<?php

namespace RockSolidSoftware\BookRental\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\InstallSchemaInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * @throws \Zend_Db_Exception
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    public function install (SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();
        $tableName = $setup->getTable(Book::table);

        if (!$connection->isTableExists($tableName)) {
            $connection->createTable($connection->newTable($tableName)
                ->addColumn('id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'  => true,
                ])
                ->addColumn('title', Table::TYPE_TEXT, 255, [
                    'nullable' => false,
                ])
                ->addColumn('author', Table::TYPE_TEXT, 255, [
                    'nullable' => true,
                ])
                ->addColumn('created_at', Table::TYPE_DATETIME, 255, [
                    'nullable' => false,
                ])
                ->addColumn('updated_at', Table::TYPE_DATETIME, 255, [
                    'nullable' => true,
                ])
                ->setOption('charset', 'utf8'));
        }

        $setup->endSetup();
    }

}
