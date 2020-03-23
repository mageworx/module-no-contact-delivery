<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Block;

class NoContactDeliveryData extends \Magento\Payment\Block\Form
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \MageWorx\NoContactDelivery\Helper\Data
     */
    protected $helperData;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \MageWorx\NoContactDelivery\Helper\Data $helperData
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageWorx\NoContactDelivery\Helper\Data $helperData,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
        $this->helperData      = $helperData;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getNoContactDeliveryData()
    {
        $result['enabled']     = $this->helperData->isEnabled();
        $result['label']       = $this->helperData->getLabel();
        $result['description'] = $this->helperData->getDescription();

        $quote = $this->checkoutSession->getQuote();

        /** @var CartExtensionInterface $extensionAttributes */
        $extensionAttributes = $quote->getExtensionAttributes();
        if (null !== $extensionAttributes &&
            null !== $extensionAttributes->getMageworxNocontactDelivery()
        ) {
            $isNoContactDelivery = $extensionAttributes->getMageworxNocontactDelivery();
            $result['value']     = $isNoContactDelivery;
        } else {
            $result['value'] = 0;
        }

        $result['shipping_methods'] = $this->helperData->getShippingMethods();
        $result['payment_methods']  = $this->helperData->getPaymentMethods();

        return $result;
    }
}
