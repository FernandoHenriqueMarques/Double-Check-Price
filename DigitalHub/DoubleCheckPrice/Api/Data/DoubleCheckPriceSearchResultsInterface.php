<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

namespace DigitalHub\DoubleCheckPrice\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DoubleCheckPriceSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return DoubleCheckPriceInterface[]
     */
    public function getItems();


    /**
     * Set items
     *
     * @param DoubleCheckPriceInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
