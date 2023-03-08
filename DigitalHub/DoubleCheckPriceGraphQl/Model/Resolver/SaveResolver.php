<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPriceGraphQl\Model\Resolver;

use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use DigitalHub\DoubleCheckPrice\Api\DoubleCheckPriceRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Save Double Check Price List
 */
class SaveResolver implements ResolverInterface
{
    /**
     * @param DoubleCheckPriceRepositoryInterface $doubleCheckPriceRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        protected DoubleCheckPriceRepositoryInterface $doubleCheckPriceRepository,
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $userId = $context->getUserId();
        if (!$userId) {
            throw new GraphQlAuthorizationException(__('The current user isn\'t authorized.'));
        }

        try {
            $data = [
                "user_id" => $userId,
                'sku' => $args['sku'],
                'attribute_name' => DoubleCheckPriceInterface::ATTRIBUTE_PRICE,
                'old_data' => $this->productRepository->get($args['sku'])->getPrice(),
                'new_data' => $args['price'],
                'status' => DoubleCheckPriceInterface::STATUS_PENDING
            ];

            return $this->doubleCheckPriceRepository->saveDoubleCheckPrice($data);

        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__('Could not proceed double check price list.'));
        }
    }
}
