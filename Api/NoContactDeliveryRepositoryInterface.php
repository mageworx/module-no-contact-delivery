<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Api;

use MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface;

interface NoContactDeliveryRepositoryInterface
{
    const ORDER_ENTITY = 'order';
    const QUOTE_ENTITY = 'quote';

    /**
     * @param \MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface $noContactDelivery
     * @return \MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(NoContactDeliveryInterface $noContactDelivery);

    /**
     * @param int $id
     * @param string $type
     * @return \MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface
     */
    public function getById($id, $type);

    /**
     * @param int $entityId
     * @param string $entityType
     * @param bool $value
     * @return \MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveData($entityId, $entityType, $value);
}
