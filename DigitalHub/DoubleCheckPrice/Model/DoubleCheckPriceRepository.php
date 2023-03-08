<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPrice\Model;

use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterfaceFactory;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceSearchResultsInterface;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceSearchResultsInterfaceFactory;
use DigitalHub\DoubleCheckPrice\Api\DoubleCheckPriceRepositoryInterface;
use DigitalHub\DoubleCheckPrice\Model\ResourceModel\DoubleCheckPrice as DoubleCheckPriceResourceModel;
use DigitalHub\DoubleCheckPrice\Model\ResourceModel\DoubleCheckPrice\CollectionFactory;
use DigitalHub\DoubleCheckPrice\Service\SetPriceByDoubleCheckPriceId;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class DoubleCheckPriceRepository implements DoubleCheckPriceRepositoryInterface
{
    /**
     * Double Check Price Repository Constructor
     *
     */
    public function __construct(
        protected DoubleCheckPriceInterfaceFactory $doubleCheckPriceInterfaceFactory,
        protected DoubleCheckPriceResourceModel $doubleCheckPriceResourceModel,
        protected DoubleCheckPrice $doubleCheckPrice,
        protected CollectionFactory $collectionFactory,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected CollectionProcessorInterface $collectionProcessor,
        protected DoubleCheckPriceSearchResultsInterfaceFactory $doubleCheckPriceSearchResultsInterfaceFactory,
        protected SetPriceByDoubleCheckPriceId $setPriceByDoubleCheckPriceId
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getById(int $entityId): DoubleCheckPriceInterface
    {
        $doubleCheckPrice = $this->doubleCheckPriceInterfaceFactory->create();
        $this->doubleCheckPriceResourceModel->load($doubleCheckPrice, $entityId);
        if (empty($doubleCheckPrice->getEntityId())) {
            throw new NoSuchEntityException(__('No such double check price entity'));
        }

        return $doubleCheckPrice;
    }

    /**
     * @inheritDoc
     */
    public function getList(?SearchCriteriaInterface $searchCriteria = null): DoubleCheckPriceSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();

        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        }

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->doubleCheckPriceSearchResultsInterfaceFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function saveDoubleCheckPrice(array $doubleCheckPrice): DoubleCheckPriceInterface
    {
        try {
            $doubleCheckPriceModel = $this->doubleCheckPrice;
            $doubleCheckPriceModel->setData($doubleCheckPrice);
            $this->doubleCheckPriceResourceModel->save($doubleCheckPriceModel);
            return $this->getById((int)$doubleCheckPriceModel->getEntityId());
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save double check price entity'));
        }
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function approveStatusDoubleCheckPrice(int $entity_id): DoubleCheckPriceInterface
    {
        return $this->updateStatusDoubleCheckPrice($entity_id, DoubleCheckPriceInterface::STATUS_APPROVE);
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function disapproveStatusDoubleCheckPrice(int $entity_id): DoubleCheckPriceInterface
    {
        return $this->updateStatusDoubleCheckPrice($entity_id, DoubleCheckPriceInterface::STATUS_DISAPPROVE);
    }

    public function updateStatusDoubleCheckPrice(int $entity_id, string $status): DoubleCheckPriceInterface
    {
        try {
            $doubleCheckPriceModel = $this->getById($entity_id);

            if ($status == DoubleCheckPriceInterface::STATUS_APPROVE) {
                $this->setPriceByDoubleCheckPriceId->execute($doubleCheckPriceModel);
            }

            $doubleCheckPriceModel->setStatus($status);
            $this->doubleCheckPriceResourceModel->save($doubleCheckPriceModel);
            return $this->getById($entity_id);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save double check price entity'));
        }
    }
}
