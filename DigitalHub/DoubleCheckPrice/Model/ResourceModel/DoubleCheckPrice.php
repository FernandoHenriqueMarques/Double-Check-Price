<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPrice\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;

/**
 * @codeCoverageIgnore
 */
class DoubleCheckPrice extends AbstractDb
{
    public const TABLE_NAME = 'digital_hub_double_check_price';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, DoubleCheckPriceInterface::ENTITY_ID);
    }
}
