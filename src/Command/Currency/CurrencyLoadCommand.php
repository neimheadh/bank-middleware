<?php

namespace App\Command\Currency;

use App\Api\Currency\CurrencyApiManager;
use App\Entity\Currency\Currency;
use App\Repository\Currency\CurrencyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Load currency in database command.
 */
#[AsCommand(
    name: 'app:currency:load',
    description: 'Import currency from CurrencyAPI.',
)]
class CurrencyLoadCommand extends Command
{

    /**
     * @param CurrencyApiManager $currencyApiManager Currency API manager.
     * @param CurrencyRepository $currencyRepository Currency repository.
     */
    public function __construct(
        private readonly CurrencyApiManager $currencyApiManager,
        private readonly CurrencyRepository $currencyRepository,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->addOption(
            'skip-currency',
            null,
            InputOption::VALUE_NONE,
            'Skip currency import.'
        )->addOption(
            'skip-coefficient',
            null,
            InputOption::VALUE_NONE,
            'Skip coefficient import.'
        );
    }

    /**
     * {@inheritDoc}
     *
     * @throws Throwable
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $importCurrency = !$input->getOption('skip-currency');
        $importCoefficient = !$input->getOption('skip-coefficient');

        try {
            if ($importCurrency) {
                $output->writeln(
                    '<comment>Import currencies:</comment>'
                );
                $currencies = $this->currencyApiManager->loadCurrencies(
                    new ProgressBar($output)
                );
                $output->writeln('');
            } else {
                /** @var Currency[] $currencies */
                $currencies = $this->currencyRepository->findAll();
            }

            if ($importCoefficient) {
                $output->writeln(
                    '<comment>Import coefficients:</comment>'
                );
                $this->currencyApiManager->loadCoefficients(
                    $currencies,
                    new ProgressBar($output)
                );
                $output->writeln('');
            }
        } catch (Throwable $exception) {
            $output->writeln('<error>Failed</error>');
            throw $exception;
        }

        return self::SUCCESS;
    }

}