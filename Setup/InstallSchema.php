<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'mageworx_no_contact_delivery_attribute'
         */
        $tableName = 'mageworx_no_contact_delivery_attribute';
        $table     = $installer->getConnection()
                               ->newTable($installer->getTable($tableName))
                               ->addColumn(
                                   'entity_id',
                                   \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                                   null,
                                   ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                                   'Entity ID'
                               )
                               ->addColumn(
                                   'related_entity_id',
                                   \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                                   null,
                                   ['unsigned' => true, 'nullable' => false],
                                   'Related Entity ID'
                               )
                               ->addColumn(
                                   'entity_type',
                                   \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                   255,
                                   ['nullable' => false],
                                   'Entity Type'
                               )
                               ->addColumn(
                                   'value',
                                   \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                                   null,
                                   ['nullable' => false, 'default' => false],
                                   'Value'
                               )
                               ->addIndex(
                                   $installer->getIdxName(
                                       'mageworx_no_contact_delivery_attribute',
                                       ['related_entity_id', 'entity_type'],
                                       \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                                   ),
                                   ['related_entity_id', 'entity_type'],
                                   ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                               )->setComment('No-Contact Delivery Table');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
