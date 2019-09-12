<?php

namespace RockSolidSoftware\BookRental\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\InstallSchemaInterface;
use RockSolidSoftware\BookRental\Model\ResourceModel\Book;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Perform installation
     *
     * @throws \Zend_Db_Exception
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();

        $this->createBookTable($setup, $connection);
        $this->createCustomerBookTable($setup, $connection);

        $setup->endSetup();
    }

    /**
     * Create table for Books
     *
     * @throws \Zend_Db_Exception
     * @param SchemaSetupInterface $setup
     * @param AdapterInterface     $connection
     */
    private function createBookTable(SchemaSetupInterface $setup, AdapterInterface $connection)
    {
        $tableName = $setup->getTable(Book::table);

        if (!$connection->isTableExists($tableName)) {
            $connection->createTable($connection->newTable($tableName)
                ->addColumn('id', Table::TYPE_INTEGER, null, [
                    Table::OPTION_IDENTITY => true,
                    Table::OPTION_UNSIGNED => true,
                    Table::OPTION_NULLABLE => false,
                    Table::OPTION_PRIMARY  => true,
                ])
                ->addColumn('title', Table::TYPE_TEXT, 255, [
                    Table::OPTION_NULLABLE => false,
                ])
                ->addColumn('author', Table::TYPE_TEXT, 255, [
                    Table::OPTION_NULLABLE => true,
                ])
                ->addColumn('created_at', Table::TYPE_DATETIME, 255, [
                    Table::OPTION_NULLABLE => false,
                ])
                ->addColumn('updated_at', Table::TYPE_DATETIME, 255, [
                    Table::OPTION_NULLABLE => true,
                ])
                ->setOption('charset', 'utf8'));
        }
    }

    /**
     * Create table for Customer Book entries
     *
     * @throws \Zend_Db_Exception
     * @param SchemaSetupInterface $setup
     * @param AdapterInterface     $connection
     */
    private function createCustomerBookTable(SchemaSetupInterface $setup, AdapterInterface $connection)
    {
        $tableName = $setup->getTable(Book::table);

        if (!$connection->isTableExists($tableName)) {
            $connection->createTable($connection->newTable($tableName)
                ->addColumn('id', Table::TYPE_INTEGER, null, [
                    Table::OPTION_IDENTITY => true,
                    Table::OPTION_UNSIGNED => true,
                    Table::OPTION_NULLABLE => false,
                    Table::OPTION_PRIMARY  => true,
                ])
                ->addColumn('customer_id', Table::TYPE_INTEGER, null, [
                    Table::OPTION_NULLABLE => false,
                ])
                ->addColumn('book_id', Table::TYPE_INTEGER, null, [
                    Table::OPTION_NULLABLE => false,
                ])
                ->addColumn('is_rented', Table::TYPE_INTEGER, 1, [
                    Table::OPTION_NULLABLE => false,
                    Table::OPTION_DEFAULT  => 1,
                ])
                ->addColumn('created_at', Table::TYPE_DATETIME, 255, [
                    Table::OPTION_NULLABLE => false,
                ])
                ->addColumn('updated_at', Table::TYPE_DATETIME, 255, [
                    Table::OPTION_NULLABLE => true,
                ])
                ->setOption('charset', 'utf8'));
        }
    }

}
