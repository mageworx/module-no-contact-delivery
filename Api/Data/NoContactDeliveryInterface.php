<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Api\Data;

interface NoContactDeliveryInterface
{
    const VALUE             = 'value';
    const ENTITY_ID         = 'entity_id';
    const ENTITY_TYPE       = 'entity_type';
    const RELATED_ENTITY_ID = 'related_entity_id';

    /**
     * Return value.
     *
     * @return boolean
     */
    public function getValue();

    /**
     * Set value.
     *
     * @param boolean $value
     * @return $this
     */
    public function setValue($value);

    /**
     * Return entity id.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set entity id.
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Return entity id.
     *
     * @return int
     */
    public function getRelatedEntityId();

    /**
     * Set entity id.
     *
     * @param int $entityId
     * @return $this
     */
    public function setRelatedEntityId($entityId);

    /**
     * Return entity type.
     *
     * @return string
     */
    public function getEntityType();

    /**
     * Set entity type.
     *
     * @param string $entityType
     * @return $this
     */
    public function setEntityType($entityType);
}
