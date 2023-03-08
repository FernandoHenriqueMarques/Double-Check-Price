<?php

namespace DigitalHub\DoubleCheckPrice\Console\Command;

use DigitalHub\DoubleCheckPrice\Api\DoubleCheckPriceRepositoryInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to set empty register status to value
 *
 * @codeCoverageIgnore
 */
class DisapproveDoubleCheckPrice extends Command
{

    /**
     * DisapproveDoubleCheckPrice constructor
     *
     */
    public function __construct(
        protected DoubleCheckPriceRepositoryInterface $doubleCheckPriceRepositoryInterface,
        protected State $state
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
        $this->setName('digitalhub:update-double-check-price:disapprove');
        $this->setDescription('Command to disapprove Double Check Price');
        $this->setDefinition(
            [
                new InputArgument(
                    "id",
                    InputArgument::REQUIRED,
                    'Double Check Price Id'
                )
            ]
        );
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
        $this->state->setAreaCode(Area::AREA_ADMINHTML);

        $output->writeln('Started setting Double Check Price status...');

        $this->doubleCheckPriceRepositoryInterface->disapproveStatusDoubleCheckPrice((int)$input->getArgument('id'));

        $output->writeln('<info>Success!</info>');
    }
}
