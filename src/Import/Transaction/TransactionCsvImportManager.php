<?php

namespace App\Import\Transaction;

use App\Entity\Account\Account;
use App\Entity\Transaction\Transaction;
use App\Filesystem\Reader\CsvReader;
use App\Repository\Transaction\TransactionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Transactions csv import manager.
 */
class TransactionCsvImportManager
{

    /**
     * Account balance after import option name.
     */
    public const OPTION_ACCOUNT_BALANCE = 'account-balance';

    /**
     * Date format.
     */
    public const OPTION_DATE_FORMAT = 'date-format';

    /**
     * Default name option name.
     */
    public const OPTION_DEFAULT_NAME = 'default-name';

    /**
     * Float decimal char.
     */
    public const OPTION_FLOAT_DECIMAL = 'float-decimal';

    /**
     * Float thousand separator char.
     */
    public const OPTION_FLOAT_THOUSAND = 'float-thousand';

    /**
     * Transaction balance header option name.
     */
    public const OPTION_HEADER_BALANCE = 'header-balance';

    /**
     * Transaction income balance option name.
     */
    public const OPTION_HEADER_BALANCE_INCOME = 'header-balance-income';

    /**
     * Transaction name header option name.
     */
    public const OPTION_HEADER_NAME = 'header-label';

    /**
     * Transaction record date header option name.
     */
    public const OPTION_HEADER_RECORD_DATE = 'header-record-date';

    /**
     * Transaction effective date header option name.
     */
    public const OPTION_HEADER_TRANSACTION_DATE = 'header-transaction-date';

    /**
     * Transaction uid header option name.
     */
    public const OPTION_HEADER_UID = 'header-uid';

    /**
     * Remove all account transactions option name.
     */
    public const OPTION_RESET_TRANSACTIONS = 'reset-transactions';

    /**
     * Signed outcome option name.
     */
    public const OPTION_SIGNED_OUTCOME = 'signed-outcome';

    /**
     * Default option values.
     */
    private const DEFAULT_OPTIONS = [
      self::OPTION_ACCOUNT_BALANCE => null,
      self::OPTION_DATE_FORMAT => 'Y-m-d',
      self::OPTION_DEFAULT_NAME => 'Unknown',
      self::OPTION_FLOAT_DECIMAL => '.',
      self::OPTION_FLOAT_THOUSAND => '',
      self::OPTION_HEADER_BALANCE => 'balance',
      self::OPTION_HEADER_BALANCE_INCOME => null,
      self::OPTION_HEADER_NAME => 'label',
      self::OPTION_HEADER_RECORD_DATE => null,
      self::OPTION_HEADER_TRANSACTION_DATE => null,
      self::OPTION_HEADER_UID => null,
      self::OPTION_RESET_TRANSACTIONS => false,
      self::OPTION_SIGNED_OUTCOME => true,
    ];

    /**
     * @param CsvReader              $csvReader CSV reader.
     * @param EntityManagerInterface $em        Entity manager.
     */
    public function __construct(
      private readonly CsvReader $csvReader,
      private readonly EntityManagerInterface $em,
      private readonly TransactionRepository $transactionRepository,
    ) {
    }

    /**
     * Import transaction in given CSV file into given account.
     *
     * @param Account              $account Transactions account.
     * @param string               $file    CSV file path.
     * @param array                $options Import options.
     * @param OutputInterface|null $output  Console output.
     *
     * @return void
     * @todo Refacto
     *
     */
    public function import(
      Account $account,
      string $file,
      array $options = [],
      ?OutputInterface $output = null,
    ): void {
        $options = array_merge(self::DEFAULT_OPTIONS, $options);

        if ($options[self::OPTION_RESET_TRANSACTIONS]) {
            $progress = $output ? new ProgressBar(
              $output,
              $account->getTransactions()->count()
            ) : null;
            $output?->writeln('<info>Clear account transactions</info>');
            $progress?->start();

            foreach ($account->getTransactions() as $transaction) {
                $account->removeTransaction($transaction);
                $this->em->remove($transaction);
                $this->em->flush();
                $progress?->advance();
            }
            $progress?->finish();
            $output?->writeln('');
        }

        $output?->writeln(
          sprintf(
            '<info>Import file <comment>%s</comment> in account <comment>%s</comment>.</info>',
            $file,
            $account->getName(),
          )
        );
        $this->csvReader->process(
          $file,
          $options,
          $output,
          function (array $line) use ($options, $account) {
              // Parse balance.
              $signed = $options[self::OPTION_SIGNED_OUTCOME];
              $balance = $line[$options[self::OPTION_HEADER_BALANCE]]
                ?? 0.0;
              if (!$balance
                && $options[self::OPTION_HEADER_BALANCE_INCOME]
                && ($line[$options[self::OPTION_HEADER_BALANCE_INCOME]] ?? null)
              ) {
                  $signed = true;
                  $balance = $line[$options[self::OPTION_HEADER_BALANCE_INCOME]];
              }
              if (is_string($balance)) {
                  $balance = str_replace(
                    [
                      $options[self::OPTION_FLOAT_DECIMAL],
                      $options[self::OPTION_FLOAT_THOUSAND],
                    ],
                    ['.', ''],
                    $balance
                  );
                  $balance = floatval($balance);

                  if (!$signed) {
                      $balance = $balance * -1.0;
                  }
              }

              // Create transaction.
              $transaction = new Transaction();

              if ($options[self::OPTION_HEADER_UID]
                && ($line[$options[self::OPTION_HEADER_UID]] ?? null)
              ) {
                  $transaction = $this->transactionRepository
                    ->findOneByUid($line[$options[self::OPTION_HEADER_UID]])
                    ?: new Transaction();
                  $transaction->setUid(
                    $line[$options[self::OPTION_HEADER_UID]]
                  );
              }

              if ($options[self::OPTION_HEADER_RECORD_DATE]
                && ($line[$options[self::OPTION_HEADER_RECORD_DATE]] ?? null)
              ) {
                  $transaction->setRecordDate(
                    DateTime::createFromFormat(
                      $options[self::OPTION_DATE_FORMAT],
                      $line[$options[self::OPTION_HEADER_RECORD_DATE]]
                    )
                  );
              }

              if ($options[self::OPTION_HEADER_TRANSACTION_DATE]
                && ($line[$options[self::OPTION_HEADER_TRANSACTION_DATE]] ?? null)
              ) {
                  $transaction->setTransactionDate(
                    DateTime::createFromFormat(
                      $options[self::OPTION_DATE_FORMAT],
                      $line[$options[self::OPTION_HEADER_TRANSACTION_DATE]]
                    )
                  );
              }

              $transaction->setAccount($account);
              $transaction->setName(
                $line[$options[self::OPTION_HEADER_NAME]]
                ?? $options[self::OPTION_DEFAULT_NAME]
              );
              $transaction->setBalance($balance);

              $this->em->persist($transaction);
              $this->em->flush();
          },
        );

        // Update account balance.
        if ($options[self::OPTION_ACCOUNT_BALANCE] !== null) {
            $account->setBalance(
              floatval($options[self::OPTION_ACCOUNT_BALANCE])
            );
            $this->em->persist($account);
            $this->em->flush();
        }
    }

}