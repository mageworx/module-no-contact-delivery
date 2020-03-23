<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Plugin;

use Magento\Quote\Api\Data\CartExtensionInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use \Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use MageWorx\NoContactDelivery\Api\NoContactDeliveryRepositoryInterface;
use MageWorx\NoContactDelivery\Api\Data\NoContactDeliveryInterface;

class AddNoContactDeliveryToQuote
{
    /**
     * @var CartExtensionFactory
     */
    private $cartExtensionFactory;

    /**
     * @var NoContactDeliveryRepositoryInterface
     */
    private $noContactDeliveryRepository;

    /**
     * AddNoContactDeliveryToCart constructor.
     *
     * @param CartExtensionFactory $cartExtensionFactory
     * @param NoContactDeliveryRepositoryInterface $noContactDeliveryRepository
     */
    public function __construct(
        CartExtensionFactory $cartExtensionFactory,
        NoContactDeliveryRepositoryInterface $noContactDeliveryRepository
    ) {
        $this->cartExtensionFactory        = $cartExtensionFactory;
        $this->noContactDeliveryRepository = $noContactDeliveryRepository;
    }

    /**
     * Set Multi Fees Data
     *
     * @param CartRepositoryInterface $subject
     * @param CartInterface $cart
     * @return CartInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        CartRepositoryInterface $subject,
        CartInterface $cart
    ) {
        /** @var CartExtensionInterface $extensionAttributes */
        $extensionAttributes = $cart->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->cartExtensionFactory->create();
        }

        /** @var NoContactDeliveryInterface $noContactDelivery */
        $noContactDelivery = $this->noContactDeliveryRepository->getById(
            $cart->getEntityId(),
            NoContactDeliveryRepositoryInterface::QUOTE_ENTITY
        );

        $extensionAttributes->setMageworxNocontactDelivery($noContactDelivery->getValue());
        $cart->setExtensionAttributes($extensionAttributes);

        return $cart;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param SearchCriteriaInterface $cartSearchResult
     * @return SearchCriteriaInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(
        CartRepositoryInterface $subject,
        SearchCriteriaInterface $cartSearchResult
    ) {
        /** @var CartInterface $entity */
        foreach ($cartSearchResult->getItems() as $cart) {
            $this->afterGet($subject, $cart);
        }

        return $cartSearchResult;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $resultCart
     * @return CartInterface
     * @throws CouldNotSaveException
     */
    public function beforeSave(CartRepositoryInterface $subject, CartInterface $resultCart)
    {
        /** @var CartExtensionInterface $extensionAttributes */
        $extensionAttributes = $resultCart->getExtensionAttributes();

        if (null !== $extensionAttributes &&
            null !== $extensionAttributes->getMageworxNocontactDelivery()
        ) {
            $isNoContactDelivery = $extensionAttributes->getMageworxNocontactDelivery();

            try {
                $this->noContactDeliveryRepository->saveData(
                    $resultCart->getId(),
                    NoContactDeliveryRepositoryInterface::QUOTE_ENTITY,
                    $isNoContactDelivery
                );
            } catch (\Exception $e) {
                throw new CouldNotSaveException(
                    __('Could not add attribute to cart: "%1"', $e->getMessage()),
                    $e
                );
            }
        }

        return [$resultCart];
    }
}
