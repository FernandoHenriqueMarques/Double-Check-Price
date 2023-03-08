<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

namespace DigitalHub\DoubleCheckPrice\Service;

use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Psr\Log\LoggerInterface;

/**
 * Service for set product price by DoubleCheckPrice ID
 */
class SetPriceByDoubleCheckPriceId
{
    /**
     * Class construct
     *
     * @param ProductRepositoryInterface $product
     */
    public function __construct(
        protected ProductRepositoryInterface $product,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * Execute the service
     *
     * @param DoubleCheckPriceInterface $doubleCheckPrice
     * @return void
     * @throws CouldNotSaveException
     */
    public function execute(DoubleCheckPriceInterface $doubleCheckPrice): void
    {
        $newPrice = $doubleCheckPrice->getNewData();

        try {
            $product = $this->product->get($doubleCheckPrice->getSku());
            $product->setPrice($newPrice);
            $this->product->save($product, true);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__('Could not save double check price entity'));
        }
    }
}
