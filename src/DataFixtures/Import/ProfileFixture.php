<?php

namespace App\DataFixtures\Import;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Entity\Currency\Currency;
use App\Entity\Import\Profile;
use App\Entity\ThirdParty\ThirdParty;
use App\Import\Processor\DataMapProcessor;
use App\Import\Processor\Parser\DateParser;
use App\Import\Processor\Parser\EntityReferenceParser;
use App\Import\Processor\Parser\FloatParser;
use App\Import\Processor\Parser\SumValueParser;
use App\Import\Reader\CsvFileReader;
use App\Import\Writer\OrmWriter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Import profile fixtures loader.
 */
class ProfileFixture extends Fixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setCode('FNBP');
        $profile->setName('Import csv FNBP');
        $profile->setReader(CsvFileReader::class);
        $profile->setProcessor(DataMapProcessor::class);
        $profile->setWriter(OrmWriter::class);
        $profile->setReaderConfiguration(['separator' => ';']);
        $profile->setProcessorConfiguration(
            [
                'data-map' => [
                    'thirdParty' => [
                        'class' => ThirdParty::class,
                        'identifier' => 'name',
                        'map' => [
                            'name' => [
                                'value' => 'Libelle simplifie',
                            ],
                        ],
                    ],
                    'transaction' => [
                        'class' => Transaction::class,
                        'map' => [
                            'account' => [
                                'parser' => [
                                    'class' => EntityReferenceParser::class,
                                    'arguments' => [
                                        '@doctrine.orm.entity_manager',
                                        Account::class,
                                        'code',
                                    ],
                                ],
                                'fixed' => 'TST',
                            ],
                            'thirdParty' => [
                                'parser' => [
                                    'class' => EntityReferenceParser::class,
                                    'arguments' => [
                                        '@doctrine.orm.entity_manager',
                                        ThirdParty::class,
                                        'name',
                                    ],
                                ],
                                'value' => 'Libelle simplifie',
                            ],
                            'currency' => [
                                'parser' => [
                                    'class' => EntityReferenceParser::class,
                                    'arguments' => [
                                        '@doctrine.orm.entity_manager',
                                        Currency::class,
                                        'code',
                                    ],
                                ],
                                'fixed' => 'EUR',
                            ],
                            'createdAt' => [
                                'parser' => [
                                    'class' => DateParser::class,
                                    'arguments' => ['d/m/Y'],
                                ],
                                'value' => 'Date operation',
                            ],
                            'name' => ['value' => 'Libelle operation'],
                            'balance' => [
                                'parser' => [
                                    'class' => SumValueParser::class,
                                    'arguments' => [
                                        [
                                            'class' => FloatParser::class,
                                            'arguments' => [
                                                'noError' => true,
                                                'ceil' => 2,
                                            ],
                                        ],
                                        [
                                            'class' => FloatParser::class,
                                            'arguments' => [
                                                'noError' => true,
                                                'floor' => 2,
                                            ],
                                        ],
                                    ],
                                ],
                                'value' => ['Debit', 'Credit'],
                            ],
                        ],
                    ],
                ],
            ],
        );
        $profile->setWriterConfiguration([]);
        $manager->persist($profile);

        $manager->flush();
    }

}