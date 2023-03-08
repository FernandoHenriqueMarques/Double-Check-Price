<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPrice\Model\ResourceModel\DoubleCheckPrice;

use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use DigitalHub\DoubleCheckPrice\Model\DoubleCheckPrice;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Database collection
 *
 * @codeCoverageIgnore
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = DoubleCheckPriceInterface::ENTITY_ID;

    /**
     * @var string
     */
    protected $_eventPrefix = 'digital_hub_double_check_price_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'double_check_price_collection';

    /**
     * Collection constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            DoubleCheckPrice::class,
            \DigitalHub\DoubleCheckPrice\Model\ResourceModel\DoubleCheckPrice::class
        );
    }
}
