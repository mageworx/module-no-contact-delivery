<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\NoContactDelivery\Block\Catalog\Product;

use MageWorx\NoContactDelivery\Helper\Data;

/**
 * Class NoContactDeliveryInfo
 */
class NoContactDeliveryInfo extends \Magento\Framework\View\Element\Template
{

    const CACHE_TAG = 'mageworx-no-contact-delivery';

    /**
     * @var Data
     */
    protected $helper;

    /**
     * NoContactDeliveryInfo constructor.
     *
     * @param Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('MageWorx_NoContactDelivery::product_info.phtml');

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getLabelForProduct()
    {
        return $this->helper->getLabelForProduct();
    }


    /**
     * @return string
     */
    public function getDescriptionForProduct()
    {
        return $this->helper->getDescriptionForProduct();
    }
}
