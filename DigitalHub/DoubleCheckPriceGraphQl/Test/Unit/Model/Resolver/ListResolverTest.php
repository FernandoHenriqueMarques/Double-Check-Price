<?php
/**
  Copyright © Magento, Inc. All rights reserved.
 */

declare(strict_types=1);

namespace DigitalHub\DoubleCheckPriceGraphQl\Test\Unit\Model\Resolver;

use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceSearchResultsInterface;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use DigitalHub\DoubleCheckPrice\Api\DoubleCheckPriceRepositoryInterface;
use DigitalHub\DoubleCheckPrice\Model\DoubleCheckPrice;
use DigitalHub\DoubleCheckPriceGraphQl\Model\Resolver\ListResolver;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \DigitalHub\DoubleCheckPriceGraphQl\Model\Resolver\ListResolver
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ListResolverTest extends TestCase
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
     * @var ListResolver
     */
    private ListResolver $objectInstance;

    /**
     * Set instances
     */
    protected function setUp(): void
    {
        $this->doubleCheckPriceRepositoryMock = $this->createMock(DoubleCheckPriceRepositoryInterface::class);
        $this->doubleCheckPriceSearchResultsMock = $this->createMock(DoubleCheckPriceSearchResultsInterface::class);
        $this->doubleCheckPriceMock = $this->createMock(DoubleCheckPrice::class);
        $this->fieldMock = $this->createMock(Field::class);
        $this->contextMock = $this->createMock(Context::class);
        $this->resolveInfoMock = $this->createMock(ResolveInfo::class);

        $this->objectInstance = new ListResolver(
            $this->doubleCheckPriceRepositoryMock
        );
    }

    /**
     * @dataProvider doubleCheckPriceDataProvider
     */
    public function testResolve($doubleCheckPriceData)
    {
        $customerId = $doubleCheckPriceData['customer_id'];

        $this->contextMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($customerId);

        $this->doubleCheckPriceRepositoryMock->expects($this->any())
            ->method('getList')
            ->willReturn($this->doubleCheckPriceSearchResultsMock);

        $this->doubleCheckPriceSearchResultsMock->expects($this->any())
            ->method('getItems')
            ->willReturn([$this->doubleCheckPriceMock]);

        $this->doubleCheckPriceMock->expects($this->any())
            ->method('getData')
            ->willReturn([1,2,3,4]);

        $result = $this->objectInstance->resolve(
            $this->fieldMock,
            $this->contextMock,
            $this->resolveInfoMock
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

        $this->contextMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($customerId);


        $exception = new LocalizedException(__('Could not proceed double check price list.'));

        $this->doubleCheckPriceRepositoryMock->expects($this->any())
            ->method('getList')
            ->willThrowException($exception);

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Could not proceed double check price list.');

        $this->objectInstance->resolve(
            $this->fieldMock,
            $this->contextMock,
            $this->resolveInfoMock
        );
    }

    /**
     * Double Check Price data provider
     */
    public function doubleCheckPriceDataProvider()
    {
        return [
            'dataShouldCustomerIdIsValid' => [
                'doubleCheckPriceData' => [
                    'customer_id' => 10
                ]
            ]
        ];
    }
}
