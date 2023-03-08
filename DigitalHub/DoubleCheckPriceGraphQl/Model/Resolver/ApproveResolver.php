<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPriceGraphQl\Model\Resolver;

use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use DigitalHub\DoubleCheckPrice\Api\DoubleCheckPriceRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Save Double Check Price List
 */
class ApproveResolver implements ResolverInterface
{
    /**
     * @param DoubleCheckPriceRepositoryInterface $doubleCheckPriceRepository
     */
    public function __construct(
        protected DoubleCheckPriceRepositoryInterface $doubleCheckPriceRepository,
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
            return $this->doubleCheckPriceRepository->approveStatusDoubleCheckPrice($args['id']);
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__('Could not proceed double check price list.'));
        }
    }
}
