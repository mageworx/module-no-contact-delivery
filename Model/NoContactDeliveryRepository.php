<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Model;

use MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface;
use MageWorx\NoContactDelivery\Api\NoContactDeliveryRepositoryInterface;
use MageWorx\NoContactDelivery\Model\ResourceModel\NoContactDelivery as NoContactDeliveryResource;
use MageWorx\NoContactDelivery\Model\NoContactDeliveryFactory;

class NoContactDeliveryRepository implements NoContactDeliveryRepositoryInterface
{
    /**
     * @var NoContactDeliveryResource
     */
    protected $resource;

    /**
     * @var NoContactDeliveryFactory
     */
    protected $noContactDeliveryFactory;

    /**
     * NoContactDeliveryRepository constructor.
     *
     * @param NoContactDeliveryResource $resource
     * @param \MageWorx\NoContactDelivery\Model\NoContactDeliveryFactory $noContactDeliveryFactory
     */
    public function __construct(
        NoContactDeliveryResource $resource,
        NoContactDeliveryFactory $noContactDeliveryFactory
    ) {
        $this->resource                 = $resource;
        $this->noContactDeliveryFactory = $noContactDeliveryFactory;
    }

    /**
     * @param NoContactDeliveryInterface $noContactDelivery
     * @return NoContactDeliveryInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(NoContactDeliveryInterface $noContactDelivery)
    {
        try {
            $this->resource->save($noContactDelivery);
        } catch (\Exception $exception) {
            throw new  \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save the No-Contact Delivery: %1',
                    $exception->getMessage()
                )
            );
        }

        return $noContactDelivery;
    }

    /**
     * @param int $id
     * @param string $type
     * @return NoContactDeliveryInterface
     */
    public function getById($id, $type)
    {
        /** @var NoContactDeliveryInterface $noContactDelivery */
        $noContactDelivery = $this->noContactDeliveryFactory->create();
        $noContactDelivery = $this->resource->loadValues($noContactDelivery, $id, $type);

        return $noContactDelivery;
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param bool $value
     * @return NoContactDeliveryInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveData($entityId, $entityType, $value)
    {
        /** @var NoContactDeliveryInterface $noContactDelivery */
        $noContactDelivery = $this->noContactDeliveryFactory->create();
        $noContactDelivery = $this->resource->loadValues($noContactDelivery, $entityId, $entityType);
        $noContactDelivery->setRelatedEntityId($entityId);
        $noContactDelivery->setEntityType($entityType);
        $noContactDelivery->setValue($value);

        return $this->save($noContactDelivery);
    }
}
