<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPrice\Api\Data;
use Magento\Catalog\Model\Product\Filter\DateTime;

interface DoubleCheckPriceInterface
{
    public const ENTITY_ID = 'entity_id';
    public const USER_ID = 'user_id';
    public const SKU = 'sku';
    public const REQUEST_DATE = 'request_date ';
    public const ATTRIBUTE_NAME = 'attribute_name';
    public const OLD_DATA = 'old_data';
    public const NEW_DATA = 'new_data';
    public const STATUS = 'status';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVE = 'approve';
    public const STATUS_DISAPPROVE = 'disapprove';

    public const ATTRIBUTE_PRICE = 'price';

    /**
     * GetSku method
     *
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * SetSku method
     *
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku): void;

    /**
     * GetRequestDate method
     *
     * @return ?string
     */
    public function getRequestDate(): ?string;

    /**
     * SetRequestDate method
     *
     * @param string $requestDate
     * @return void
     */
    public function setRequestDate(string $requestDate): void;

    /**
     * GetAttributeName method
     *
     * @return string|null
     */
    public function getAttributeName(): ?string;

    /**
     * SetAttributeName method
     *
     * @param string $attributeName
     * @return void
     */
    public function setAttributeName(string $attributeName): void;

    /**
     * GetOldData method
     *
     * @return string|null
     */
    public function getOldData(): ?string;

    /**
     * SetOldData method
     *
     * @param string $oldData
     * @return void
     */
    public function setOldData(string $oldData): void;

    /**
     * GetNewData method
     *
     * @return string|null
     */
    public function getNewData(): ?string;

    /**
     * SetNewData method
     *
     * @param string $newData
     * @return void
     */
    public function setNewData(string $newData): void;

    /**
     * GetStatus method
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * SetStatus method
     *
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void;

}
