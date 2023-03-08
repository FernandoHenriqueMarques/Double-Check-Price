<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPrice\Model;

use Magento\Framework\Model\AbstractModel;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;

/**
 * Model Double Check Price
 *
 * @codeCoverageIgnore
 */
class DoubleCheckPrice extends AbstractModel implements DoubleCheckPriceInterface
{
    /**
     * Initialize the resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\DoubleCheckPrice::class);
    }

    /**
     * @inheritDoc
     */
    public function getSku(): ?string
    {
        return $this->getData(static::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku(string $sku): void
    {
        $this->setData(static::SKU, $sku);
    }

    /**
     * @inheritDoc
     */
    public function getRequestDate(): ?string
    {
        return $this->getData(static::REQUEST_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setRequestDate(string $requestDate): void
    {
        $this->setData(static::REQUEST_DATE, $requestDate);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeName(): ?string
    {
        return $this->getData(static::ATTRIBUTE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setAttributeName(string $attributeName): void
    {
        $this->setData(static::ATTRIBUTE_NAME, $attributeName);
    }

    /**
     * @inheritDoc
     */
    public function getOldData(): ?string
    {
        return $this->getData(static::OLD_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setOldData(string $oldData): void
    {
        $this->setData(static::OLD_DATA, $oldData);
    }

    /**
     * @inheritDoc
     */
    public function getNewData(): ?string
    {
        return $this->getData(static::NEW_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setNewData(string $newData): void
    {
        $this->setData(static::NEW_DATA, $newData);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?string
    {
        return $this->getData(static::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): void
    {
        $this->setData(static::STATUS, $status);
    }
}
