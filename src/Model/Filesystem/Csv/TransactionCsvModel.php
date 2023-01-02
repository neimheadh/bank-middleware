<?php

namespace App\Model\Filesystem\Csv;

use App\Filesystem\Reader\CsvReader;
use App\Import\Transaction\TransactionCsvImportManager;

/**
 * Transaction CSV model attributes.
 */
interface TransactionCsvModel
{

    public const DEFAULT_OPTIONS = [
      'fr-bp' => [
        CsvReader::OPTION_HAS_HEADER => true,
        CsvReader::OPTION_ESCAPE => '\\',
        CsvReader::OPTION_ENCLOSURE => '"',
        CsvReader::OPTION_SEPARATOR => ';',
        TransactionCsvImportManager::OPTION_DATE_FORMAT => 'd/m/Y',
        TransactionCsvImportManager::OPTION_FLOAT_DECIMAL => ',',
        TransactionCsvImportManager::OPTION_FLOAT_THOUSAND => ' ',
        TransactionCsvImportManager::OPTION_HEADER_BALANCE => 'Debit',
        TransactionCsvImportManager::OPTION_HEADER_BALANCE_INCOME => 'Credit',
        TransactionCsvImportManager::OPTION_HEADER_NAME => 'Libelle operation',
        TransactionCsvImportManager::OPTION_HEADER_RECORD_DATE => 'Date operation',
        TransactionCsvImportManager::OPTION_HEADER_TRANSACTION_DATE => 'Date de valeur',
        TransactionCsvImportManager::OPTION_SIGNED_OUTCOME => true,
      ],
    ];

}