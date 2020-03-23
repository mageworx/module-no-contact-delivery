<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Model\ResourceModel;

use MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface;

/**
 * Class NoContactDelivery
 *
 * @package MageWorx\NoContactDelivery\Model\ResourceModel
 */
class NoContactDelivery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'mageworx_no_contact_delivery';
    protected $_eventObject = 'mageworx_no_contact_delivery';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageworx_no_contact_delivery_attribute', 'entity_id');
    }

    /**
     * @param NoContactDeliveryInterface $noContactDelivery
     * @param int $id
     * @param string $type
     */
    public function loadValues($noContactDelivery, $id, $type)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()->from(
            $this->getTable($this->getMainTable())
        )->where(NoContactDeliveryInterface::RELATED_ENTITY_ID . '= ?', $id)
                                 ->where(NoContactDeliveryInterface::ENTITY_TYPE . ' = ?', $type);
        $data       = $connection->fetchRow($select);

        if ($data) {
            $noContactDelivery->setData($data);
        } else {
            $noContactDelivery->setRelatedEntityId($id);
            $noContactDelivery->setRelatedEntityTYpe($type);
        }

        return $noContactDelivery;
    }
}
