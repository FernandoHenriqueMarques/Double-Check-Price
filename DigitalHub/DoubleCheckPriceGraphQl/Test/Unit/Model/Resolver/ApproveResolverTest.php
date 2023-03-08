<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPriceGraphQl\Test\Unit\Model\Resolver;

use DigitalHub\DoubleCheckPrice\Api\DoubleCheckPriceRepositoryInterface;
use DigitalHub\DoubleCheckPrice\Model\DoubleCheckPrice;
use DigitalHub\DoubleCheckPriceGraphQl\Model\Resolver\ApproveResolver;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \DigitalHub\DoubleCheckPriceGraphQl\Model\Resolver\ApproveResolver
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ApproveResolverTest extends TestCase
{

    /**
     * @var MockObject|DoubleCheckPriceRepositoryInterface
     */
    private MockObject|DoubleCheckPriceRepositoryInterface $doubleCheckPriceRepositoryMock;

    /**
     * @var MockObject|DoubleCheckPrice
     */
    private MockObject|DoubleCheckPrice $doubleCheckPriceMock;

    /**
     * @var ApproveResolver
     */
    private ApproveResolver $objectInstance;

    /**
     * Set instances
     */
    protected function setUp(): void
    {
        $this->doubleCheckPriceRepositoryMock = $this->createMock(DoubleCheckPriceRepositoryInterface::class);
        $this->doubleCheckPriceMock = $this->createMock(DoubleCheckPrice::class);
        $this->fieldMock = $this->createMock(Field::class);
        $this->contextMock = $this->createMock(Context::class);
        $this->resolveInfoMock = $this->createMock(ResolveInfo::class);

        $this->objectInstance = new ApproveResolver(
            $this->doubleCheckPriceRepositoryMock
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

        $this->doubleCheckPriceRepositoryMock->expects($this->any())
            ->method('approveStatusDoubleCheckPrice')
            ->willReturn($this->doubleCheckPriceMock);

        $result = $this->objectInstance->resolve(
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

        $exception = $this->createMock(CouldNotSaveException::class);

        $this->doubleCheckPriceRepositoryMock->expects($this->any())
            ->method('approveStatusDoubleCheckPrice')
            ->willThrowException($exception);

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Could not proceed double check price list.');

        $result = $this->objectInstance->resolve(
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
                    'args' => ["id" => 20]
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
