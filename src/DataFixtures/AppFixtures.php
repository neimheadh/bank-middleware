<?php

namespace App\DataFixtures;

use App\Block\Account\QuickTransactionBlock;
use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Entity\Block\Block;
use App\Entity\Currency\Currency;
use App\Entity\Import\Profile;
use App\Entity\ThirdParty\ThirdParty;
use App\Entity\User\User;
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
 * Load fixtures.
 */
class AppFixtures extends Fixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('test');
        $user->setEmail('test@example.com');
        $user->setEnabled(true);
        $user->setPlainPassword('test');
        $manager->persist($user);

        $usd = new Currency();
        $usd->setSymbol('$');
        $usd->setName('Dollar');
        $usd->setNativeSymbol('$');
        $usd->setDecimalDigits(2);
        $usd->setRounded(2);
        $usd->setCode('USD');
        $usd->setPluralName('Dollars');
        $usd->setUsdExchangeRate(1);
        $manager->persist($usd);

        $eur = new Currency();
        $eur->setSymbol('€');
        $eur->setName('Euro');
        $eur->setNativeSymbol('€');
        $eur->setDecimalDigits(2);
        $eur->setRounded(2);
        $eur->setCode('EUR');
        $eur->setPluralName('Euros');
        $eur->setUsdExchangeRate(1.09);
        $eur->setDefault(true);
        $manager->persist($eur);
        $manager->flush();

        $account = new Account();
        $account->setName('Test');
        $account->setCode('TST');
        $account->setBalance(0);
        $manager->persist($account);
        $manager->flush();

        $block = new Block();
        $block->setClass(QuickTransactionBlock::class);
        $block->setType(Block::TYPE_DASHBOARD);
        $block->setPosition(Block::POSITION_TOP);
        $block->setSettings(['account' => $account->getId()]);
        $manager->persist($block);

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
