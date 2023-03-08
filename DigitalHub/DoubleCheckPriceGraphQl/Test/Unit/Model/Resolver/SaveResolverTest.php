<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPriceGraphQl\Test\Unit\Model\Resolver;

use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceSearchResultsInterface;
use DigitalHub\DoubleCheckPrice\Api\DoubleCheckPriceRepositoryInterface;
use DigitalHub\DoubleCheckPrice\Model\DoubleCheckPrice;
use DigitalHub\DoubleCheckPriceGraphQl\Model\Resolver\SaveResolver;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \DigitalHub\DoubleCheckPriceGraphQl\Model\Resolver\SaveResolver
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SaveResolverTest extends TestCase
{

    /**
     * @var MockObject|DoubleCheckPriceRepositoryInterface
     */
    private MockObject|DoubleCheckPriceRepositoryInterface $doubleCheckPriceRepositoryMock;

    /**
     * @var DoubleCheckPriceSearchResultsInterface|MockObject
     */
    private MockObject|DoubleCheckPriceSearchResultsInterface $doubleCheckPriceSearchResultsMock;

    /**
     * @var DoubleCheckPriceInterface|MockObject
     */
    private MockObject|DoubleCheckPriceInterface $doubleCheckPriceMock;

    /**
     * @var ProductRepositoryInterface|MockObject
     */
    private MockObject|ProductRepositoryInterface $productRepositoryMock;

    /**
     * @var Product|MockObject
     */
    private MockObject|Product $productMock;

    /**
     * @var SaveResolver
     */
    private SaveResolver $objectInstance;

    /**
     * Set instances
     */
    protected function setUp(): void
    {
        $this->doubleCheckPriceRepositoryMock = $this->createMock(DoubleCheckPriceRepositoryInterface::class);
        $this->doubleCheckPriceSearchResultsMock = $this->createMock(DoubleCheckPriceSearchResultsInterface::class);
        $this->doubleCheckPriceMock = $this->createMock(DoubleCheckPrice::class);
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->productMock = $this->createMock(Product::class);
        $this->fieldMock = $this->createMock(Field::class);
        $this->contextMock = $this->createMock(Context::class);
        $this->resolveInfoMock = $this->createMock(ResolveInfo::class);

        $this->objectInstance = new SaveResolver(
            $this->doubleCheckPriceRepositoryMock,
            $this->productRepositoryMock
        );
    }

    /**
     * @dataProvider doubleCheckPriceDataProvider
     */
    public function testResolve($doubleCheckPriceData)
    {
        $customerId = $doubleCheckPriceData['customer_id'];
        $args = $doubleCheckPriceData['args'];

        $this->contextMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($customerId);

        $this->productRepositoryMock->expects($this->once())
            ->method('get')
            ->with($args['sku'])
            ->willReturn($this->productMock);

        $this->productMock->expects($this->once())
            ->method('getPrice')
            ->willReturn(100);

        $this->doubleCheckPriceRepositoryMock->expects($this->any())
            ->method('saveDoubleCheckPrice')
            ->willReturn($this->doubleCheckPriceMock);

        $this->objectInstance->resolve(
            $this->fieldMock,
            $this->contextMock,
            $this->resolveInfoMock,
            null,
            $args
        );
    }

    public function testResolveErrorCustomerIdEmpty()
    {
        $customerId = 0;

        $this->contextMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($customerId);

        $this->expectException(GraphQlAuthorizationException::class);
        $this->expectExceptionMessage('The current user isn\'t authorized.');

        $this->objectInstance->resolve(
            $this->fieldMock,
            $this->contextMock,
            $this->resolveInfoMock
        );
    }

    /**
     * @dataProvider doubleCheckPriceDataProvider
     */
    public function testResolveErrorProceedDoubleCheckPrice($doubleCheckPriceData)
    {
        $customerId = $doubleCheckPriceData['customer_id'];
        $args = $doubleCheckPriceData['args'];

        $this->contextMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($customerId);

        $this->productRepositoryMock->expects($this->once())
            ->method('get')
            ->with($args['sku'])
            ->willReturn($this->productMock);

        $this->productMock->expects($this->once())
            ->method('getPrice')
            ->willReturn(100);

        $exception = new LocalizedException(__('Could not proceed double check price list.'));
        $this->doubleCheckPriceRepositoryMock->expects($this->any())
            ->method('saveDoubleCheckPrice')
            ->willThrowException($exception);

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Could not proceed double check price list.');

        $this->objectInstance->resolve(
            $this->fieldMock,
            $this->contextMock,
            $this->resolveInfoMock,
            null,
            $args
        );
    }

    /**
     * Double Check Price data provider
     */
    public function doubleCheckPriceDataProvider()
    {
        return [
            'dataShouldCustomerIdIsValid_Exemple_1' => [
                'doubleCheckPriceData' => [
                    'customer_id' => 10,
                    'args' => [
                        "id" => 20,
                        "sku" => "abc123",
                        "price" => 200
                    ]
                ]
            ],
//            'dataShouldCustomerIdIsValid_Exemple_2' => [
//                'doubleCheckPriceData' => [
//                    'customer_id' => 11,
//                    'args' => ["id" => 21]
//                ]
//            ]
        ];
    }
}
