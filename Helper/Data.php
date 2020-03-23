<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

/**
 * GoogleApi data helper
 *
 */

namespace MageWorx\NoContactDelivery\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 */
class Data extends AbstractHelper
{
    /**
     * XML config path enable
     */
    const XML_PATH_ENABLE = 'mageworx_nocontactdelivery/general/enabled';

    /**
     * XML config path for payment methods
     */
    const XML_PATH_PAYMENT_METHODS = 'mageworx_nocontactdelivery/general/payment_methods';

    /**
     * XML config path for shipping methods
     */
    const XML_PATH_SHIPPING_METHODS = 'mageworx_nocontactdelivery/general/shipping_methods';

    /**
     * XML config path for label
     */
    const XML_PATH_LABEL = 'mageworx_nocontactdelivery/general/label';

    /**
     * XML config path for shipping methods
     */
    const XML_PATH_DESCRIPTION = 'mageworx_nocontactdelivery/general/description';

    /**
     * XML config path for message
     */
    const XML_PATH_PRODUCT_LABEL_FOR_PRODUCT = 'mageworx_nocontactdelivery/general/label_for_product';

    /**
     * XML config path for message
     */
    const XML_PATH_DESCRIPTION_FOR_PRODUCT = 'mageworx_nocontactdelivery/general/description_for_product';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Data constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param int $storeId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isEnabled($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPaymentMethods($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        $result = $this->scopeConfig->getValue(
            self::XML_PATH_PAYMENT_METHODS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $result = strtolower($result);

        return array_filter(explode(',', $result));
    }

    /**
     * @param int $storeId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getShippingMethods($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        $result = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPING_METHODS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $result = strtolower($result);

        return array_filter(explode(',', $result));
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLabel($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_LABEL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDescription($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_DESCRIPTION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLabelForProduct($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_LABEL_FOR_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDescriptionForProduct($storeId = 0)
    {
        $storeId = $storeId ? $storeId : $this->getStoreId();

        return $this->scopeConfig->getValue(
            self::XML_PATH_DESCRIPTION_FOR_PRODUCT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
