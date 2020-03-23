<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Controller\Value;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;

/**
 * Class Set
 */
class Set extends Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var CartExtensionFactory
     */
    private $cartExtensionFactory;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * Set constructor.
     *
     * @param CartRepositoryInterface $cartRepository
     * @param CartExtensionFactory $cartExtensionFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param Context $context
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        CartExtensionFactory $cartExtensionFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        Context $context
    ) {
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->checkoutSession      = $checkoutSession;
        $this->cartRepository       = $cartRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $value = $this->getRequest()->getPost('value');

        switch ($value) {
            case '1':
                $value = true;
                break;
            case '0':
                $value = false;
                break;
            default:
                $this->getResponse()->setBody(
                    json_encode(['status' => 'fail', 'message' => __('Invalid value type')])
                );

                return;
        }

        $cart = $this->checkoutSession->getQuote();
        /** @var CartExtensionInterface $extensionAttributes */
        $extensionAttributes = $cart->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->cartExtensionFactory->create();
        }

        $extensionAttributes->setMageworxNocontactDelivery($value);
        $cart->setExtensionAttributes($extensionAttributes);
        $this->cartRepository->save($cart);

        $this->getResponse()->setBody(json_encode(['status' => 'success']));
    }
}
