<?php

namespace App\Command\Transaction;

use App\Exception\Entity\Account\AccountNotFoundException;
use App\Exception\Import\UnknownCsvModelException;
use App\Import\Transaction\TransactionCsvImportManager;
use App\Model\Filesystem\Csv\TransactionCsvModel;
use App\Repository\Account\AccountRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Transaction import command.
 */
#[AsCommand(
  name: 'app:transaction:import:csv',
  description: 'Import transactions.',
  aliases: ['app:transaction:import'],
)]
class TransactionImportCsvCommand extends Command
{

    public function __construct(
      private AccountRepository $accountRepository,
      private TransactionCsvImportManager $transactionCsvImportManager,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->addArgument(
          'account_id',
          InputArgument::REQUIRED,
          'Account id.'
        )->addArgument(
          'file',
          InputArgument::REQUIRED,
          'Transaction file.',
        )->addArgument(
          'model',
          InputArgument::OPTIONAL,
          'Transaction file model.',
        )->addOption(
          TransactionCsvImportManager::OPTION_ACCOUNT_BALANCE,
          'b',
          InputOption::VALUE_REQUIRED,
          'Account balance after import.',
        )->addOption(
          TransactionCsvImportManager::OPTION_RESET_TRANSACTIONS,
          null,
          InputOption::VALUE_NONE,
          'Remove all account transactions before import.',
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(
      InputInterface $input,
      OutputInterface $output
    ): int {
        $account = $this->accountRepository->find(
          $input->getArgument('account_id')
        );
        $file = $input->getArgument('file');
        $model = $input->getArgument('model');

        if ($account === null) {
            throw new AccountNotFoundException(
              ['id' => $input->getArgument('account_id')]
            );
        }

        if ($model !== null && !array_key_exists(
            $model,
            TransactionCsvModel::DEFAULT_OPTIONS
          )) {
            throw new UnknownCsvModelException($model);
        }

        $defaults = $model ? TransactionCsvModel::DEFAULT_OPTIONS[$model] : [];
        $options = array_merge($defaults, $input->getOptions());
        foreach ($options as $name => &$value) {
            if ($value === null && ($defaults[$name] ?? null) !== null) {
                $value = $defaults[$name];
            }
        }

        $this->transactionCsvImportManager->import(
          $account,
          $file,
          $options,
          $output
        );

        return self::SUCCESS;
    }

}