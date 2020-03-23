<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Plugin;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use MageWorx\NoContactDelivery\Api\NoContactDeliveryRepositoryInterface;
use MageWorx\NoContactDelivery\Helper\Data;
use Magento\Quote\Api\CartManagementInterface;

class AddNoContactDeliveryToNewOrder
{
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
     * @param CartManagementInterface $subject
     * @param OrderInterface $order
     * @param CartInterface $cart
     * @param array $orderData
     * @return OrderInterface
     */
    public function afterSubmit(
        CartManagementInterface $subject,
        OrderInterface $order,
        CartInterface $cart,
        $orderData = []
    ) {
        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        $noContactDelivery   = $this->noContactDeliveryRepository->getById(
            $cart->getId(),
            NoContactDeliveryRepositoryInterface::QUOTE_ENTITY
        );
        $isNoContactDelivery = $noContactDelivery->getValue();

        if ($isNoContactDelivery && $this->validate($cart)) {
            $extensionAttributes->setMageworxNocontactDelivery($isNoContactDelivery);
            $order->setExtensionAttributes($extensionAttributes);

            $this->noContactDeliveryRepository->saveData(
                $order->getEntityId(),
                NoContactDeliveryRepositoryInterface::ORDER_ENTITY,
                $isNoContactDelivery
            );
        }

        return $order;
    }

    /**
     * @param CartInterface $cart
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function validate($cart)
    {
        $shippingMethod = $cart->getShippingAddress()->getShippingMethod();
        if (array_search($shippingMethod, $this->helper->getShippingMethods()) === false) {
            return false;
        }

        $paymentMethod = $cart->getPayment()->getMethod();
        if (array_search($paymentMethod, $this->helper->getPaymentMethods()) === false) {
            return false;
        }

        return true;
    }
}
