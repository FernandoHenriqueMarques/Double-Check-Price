<?php

namespace DigitalHub\DoubleCheckPrice\Console\Command;

use DigitalHub\DoubleCheckPrice\Api\DoubleCheckPriceRepositoryInterface;
use DigitalHub\DoubleCheckPrice\Api\Data\DoubleCheckPriceInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to set empty register status to value
 *
 * @codeCoverageIgnore
 */
class ListDoubleCheckPricePending extends Command
{

    /**
     * ListDoubleCheckPricePending constructor
     *
     */
    public function __construct(
        protected DoubleCheckPriceRepositoryInterface $doubleCheckPriceRepositoryInterface,
        protected SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct();
    }

    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('digitalhub:list-double-check-price');
        $this->setDescription('Command to list Double Check Price Pending');
        parent::configure();
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Started setting Double Check Price list...');

        $searchCriteria = clone $this->searchCriteriaBuilder->addFilter(
            'status',
            DoubleCheckPriceInterface::STATUS_PENDING,
            'eq'
        )->create();

        $list = $this->doubleCheckPriceRepositoryInterface->getList($searchCriteria)->getItems();

        return array_map(function ($itemsList) use ($output) {
            $item = $itemsList->getData();
            $entityId = $item["entity_id"];
            $userId = $item["user_id"];
            $sku = $item["sku"];
            $oldData = $item["old_data"];
            $newData = $item["new_data"];
            $status = $item["status"];
            $requestDate = $item["request_date"];
            $output->writeln(
                "<info>
                    entity_id: $entityId
                    user_id: $userId
                    sku: $sku
                    old_data: $oldData
                    new_data: $newData
                    status: $status
                    request_date: $requestDate
                </info>"
            );
        }, $list);
    }
}
