<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Model;

use MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface;

class NoContactDelivery extends \Magento\Framework\Model\AbstractModel implements NoContactDeliveryInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\MageWorx\NoContactDelivery\Model\ResourceModel\NoContactDelivery::class);
    }

    /**
     * @return boolean
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * @param boolean $value
     * @return NoContactDeliveryInterface
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * Return id
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Return entity type.
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * Set entity type.
     *
     * @param string $entityType
     * @return $this
     */
    public function setEntityType($entityType)
    {
        return $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * Return entity id.
     *
     * @return int
     */
    public function getRelatedEntityId()
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * Set entity id.
     *
     * @param int $entityId
     * @return $this
     */
    public function setRelatedEntityId($entityId)
    {
        return $this->setData(self::RELATED_ENTITY_ID, $entityId);
    }
}
