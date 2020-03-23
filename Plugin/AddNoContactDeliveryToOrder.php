<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Plugin;

use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use MageWorx\NoContactDelivery\Api\NoContactDeliveryRepositoryInterface;
use MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface;
use MageWorx\NoContactDelivery\Helper\Data;

class AddNoContactDeliveryToOrder
{
    const DESCRIPTION_SEPARATOR = ', ';
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var NoContactDeliveryRepositoryInterface
     */
    private $noContactDeliveryRepository;

    /**
     * @var Data
     */
    private $helper;

    /**
     * AddNoContactDeliveryToOrder constructor.
     *
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param NoContactDeliveryRepositoryInterface $noContactDeliveryRepository
     * @param Data $helper
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        NoContactDeliveryRepositoryInterface $noContactDeliveryRepository,
        Data $helper
    ) {
        $this->orderExtensionFactory       = $orderExtensionFactory;
        $this->noContactDeliveryRepository = $noContactDeliveryRepository;
        $this->helper                      = $helper;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {
        $description = $this->deleteNoContactMessage($order->getShippingDescription());

        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        /** @var NoContactDeliveryInterface $noContactDelivery */
        $noContactDelivery = $this->noContactDeliveryRepository->getById(
            $order->getEntityId(),
            NoContactDeliveryRepositoryInterface::ORDER_ENTITY
        );

        $extensionAttributes->setMageworxNocontactDelivery($noContactDelivery->getValue());
        $order->setExtensionAttributes($extensionAttributes);

        if ($noContactDelivery->getValue()) {
            $order->setShippingDescription(
                $description . self::DESCRIPTION_SEPARATOR .
                $this->helper->getLabel()
            );
        }

        return $order;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $orderSearchResult
     * @return OrderSearchResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $orderSearchResult
    ) {
        /** @var OrderInterface $entity */
        foreach ($orderSearchResult->getItems() as $order) {
            $this->afterGet($subject, $order);
        }

        return $orderSearchResult;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $resultOrder
     * @return OrderInterface
     * @throws CouldNotSaveException
     */
    public function afterSave(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderInterface $resultOrder
    ) {
        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $resultOrder->getExtensionAttributes();
        if (null !== $extensionAttributes &&
            null !== $extensionAttributes->getMageworxNocontactDelivery()
        ) {
            $isNoContactDelivery = $extensionAttributes->getMageworxNocontactDelivery();
            try {
                $this->noContactDeliveryRepository->saveData(
                    $resultOrder->getEntityId(),
                    NoContactDeliveryRepositoryInterface::ORDER_ENTITY,
                    $isNoContactDelivery
                );
            } catch (\Exception $e) {
                throw new CouldNotSaveException(
                    __('Could not add attribute to order: "%1"', $e->getMessage()),
                    $e
                );
            }
        }

        return $resultOrder;
    }

    /**
     * @param OrderInterface $entity
     * @param OrderInterface $cart
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSave(
        \Magento\Sales\Api\OrderRepositoryInterface $entity,
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        $shippingDescription = $this->deleteNoContactMessage($order->getShippingDescription());
        $extensionAttributes = $order->getExtensionAttributes();
        if (null !== $extensionAttributes &&
            null !== $extensionAttributes->getMageworxNocontactDelivery()
        ) {
            $isNoContactDelivery = $extensionAttributes->getMageworxNocontactDelivery();

            if ($isNoContactDelivery) {
                $order->setShippingDescription(
                    $shippingDescription . self::DESCRIPTION_SEPARATOR .
                    $this->helper->getLabel()
                );
            }
        }

        return [$order];
    }

    /**
     * @param string $description
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function deleteNoContactMessage($description)
    {
        return str_replace(
            self::DESCRIPTION_SEPARATOR . $this->helper->getLabel(),
            '',
            $description
        );
    }
}
