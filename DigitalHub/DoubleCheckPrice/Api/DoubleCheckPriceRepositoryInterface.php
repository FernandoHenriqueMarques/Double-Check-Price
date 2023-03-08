<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPrice\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceSearchResultsInterface;

interface DoubleCheckPriceRepositoryInterface
{
    /**
     * Get info about double check price by id
     *
     * @param int $entityId
     * @return DoubleCheckPriceInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId): DoubleCheckPriceInterface;

    /**
     * Get double check price list
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return DoubleCheckPriceSearchResultsInterface
     */
    public function getList(?SearchCriteriaInterface $searchCriteria = null): DoubleCheckPriceSearchResultsInterface;
    /**
     * Save double check price
     *
     * @param array $doubleCheckPrice
     *
     * @return DoubleCheckPriceInterface
     */
    public function saveDoubleCheckPrice(array $doubleCheckPrice): DoubleCheckPriceInterface;

    /**
     * Approve status double check price
     *
     * @param int $entity_id
     * @return DoubleCheckPriceInterface
     */
    public function approveStatusDoubleCheckPrice(int $entity_id): DoubleCheckPriceInterface;

    /**
     * Disapprove status double check price
     *
     * @param int $entity_id
     * @return DoubleCheckPriceInterface
     */
    public function disapproveStatusDoubleCheckPrice(int $entity_id): DoubleCheckPriceInterface;
}
