<?php

namespace App\Tests\Import\Processor;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Import\Exception\InputNotSupportedException;
use App\Import\Exception\MissingConfigurationException;
use App\Import\Exception\Processor\IdentifierNotFoundException;
use App\Import\Exception\Processor\ItemClassException;
use App\Import\Exception\Processor\MissingFieldException;
use App\Import\Exception\Processor\MissingParserClassException;
use App\Import\Exception\Processor\ParserClassException;
use App\Import\Processor\DataMapProcessor;
use App\Import\Processor\Parser\FloatParser;
use App\Import\Processor\Parser\ParserInterface;
use App\Import\Processor\Result\IteratorTransformer;
use Iterator;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Throwable;
use Traversable;

/**
 * DataMapProcessor test suite.
 */
class DataMapProcessorTest extends KernelTestCase
{

    /**
     * Test DataMapProcessor with a simple single entity data input.
     *
     * @return void
     * @return void
     * @throws Throwable
     *
     */
    public function testSimpleSingleEntity(): void
    {
        $processor = new DataMapProcessor(static::getContainer());
        $input = [
            'Account name' => 'Test account',
            'Code' => '00001',
            'Current balance' => '49.3',
        ];
        $dataMap = [
            Account::class => [
                'map' => [
                    'name' => ['value' => 'Account name'],
                    'code' => ['value' => 'Code'],
                    'balance' => [
                        'value' => 'Current balance',
                        'parser' => [
                            'class' => FloatParser::class,
                            'arguments' => ['floor' => 2],
                        ],
                    ],
                ],
            ],
        ];

        $result = $processor->process($input, [
            DataMapProcessor::OPTION_DATA_MAP => $dataMap,
        ]);
        $this->assertCount(1, $result);
        $result = $result[0];
        $this->assertCount(1, $result);

        /** @var Account $account */
        $account = current($result);
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('Test account', $account->getName());
        $this->assertEquals('00001', $account->getCode());
        $this->assertSame(49.3, $account->getBalance());
    }

    /**
     * Test DataMapProcessor with a simple multiple entity data input.
     *
     * @return void
     * @return void
     * @throws Throwable
     *
     */
    public function testMultipleSimpleInput(): void
    {
        $processor = new DataMapProcessor(static::getContainer());
        $input = [
            [
                'Account name' => 'Test account 1',
                'Code' => '00001',
                'Current balance' => '49.3',
            ],
            [
                'Account name' => 'Test account 2',
                'Code' => '00002',
                'Current balance' => '-21.3',
            ],
        ];
        $dataMap = [
            Account::class => [
                'map' => [
                    'name' => 'Account name',
                    'code' => ['value' => 'Code'],
                    'balance' => [
                        'value' => 'Current balance',
                        'parser' => [
                            'class' => FloatParser::class,
                            'arguments' => ['floor' => 2],
                        ],
                    ],
                ],
            ],
        ];

        $result = $processor->process($input, [
            DataMapProcessor::OPTION_DATA_MAP => $dataMap,
        ]);
        $this->assertCount(2, $result);

        /** @var Account $account */
        $account = $result[0][0];
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('Test account 1', $account->getName());
        $this->assertEquals('00001', $account->getCode());
        $this->assertSame(49.3, $account->getBalance());

        /** @var Account $account */
        $account = $result[1][0];
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('Test account 2', $account->getName());
        $this->assertEquals('00002', $account->getCode());
        $this->assertSame(-21.3, $account->getBalance());
    }

    /**
     * Test processing with an item parser.
     *
     * @test
     * @functional
     *
     * @return void
     * @throws Throwable
     */
    public function testItemParser(): void
    {
        $processor = new DataMapProcessor(static::getContainer());
        $dataMap = [
            Account::class => [
                'parser' => [
                    'class' => FakeItemParser::class,
                ],
            ],
        ];

        $result = $processor->process(['name' => '000001'], [
            DataMapProcessor::OPTION_DATA_MAP => $dataMap,
        ]);

        $this->assertCount(1, $result);
        $result = $result[0];
        $this->assertCount(1, $result);

        /** @var Transaction $transaction */
        $transaction = $result[0];
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('000001', $transaction->getName());
    }

    /**
     * Test with an iterator as input.
     *
     * @test
     * @functionnal
     *
     * @return void
     * @throws Throwable
     */
    public function testIteratorInput(): void
    {
        $processor = new DataMapProcessor(static::getContainer());
        $dataMap = [
            Account::class => [
                'map' => [
                    'name' => ['value' => 'name'],
                ],
            ],
        ];

        $input = new TestInput();
        $this->assertInstanceOf(Traversable::class, $input);

        $result = $processor->process([$input], [
            DataMapProcessor::OPTION_DATA_MAP => $dataMap,
        ]);
        $this->assertCount(1, $result);
        $result = $result[0];
        $this->assertCount(1, $result);

        /** @var Account $account */
        $account = $result[0];
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('000001', $account->getName());

        $result = $processor->process($input, [
            DataMapProcessor::OPTION_DATA_MAP => $dataMap,
        ]);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(IteratorTransformer::class, $result);
        $result = $result->current();
        $this->assertCount(1, $result);

        /** @var Account $account */
        $account = $result[0];
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('000001', $account->getName());
    }

    /**
     * Test identifier system.
     *
     * @test
     * @functional
     *
     * @return void
     * @throws Throwable
     */
    public function testIdentifiers(): void
    {
        $processor = new DataMapProcessor(static::getContainer());

        $result = $processor->process([
            [
                'account_code' => '000001',
                'transaction_name' => 'Transaction 1',
            ],
            [
                'account_code' => '000001',
                'transaction_name' => 'Transaction 2',
            ],
        ], [
            DataMapProcessor::OPTION_DATA_MAP => [
                'account' => [
                    'class' => Account::class,
                    'identifier' => 'code',
                    'map' => [
                        'code' => ['value' => 'account_code'],
                        'name' => ['fixed' => 'Test account'],
                    ],
                ],
                Transaction::class => [
                    'map' => [
                        'account' => ['reference' => 'account.000001'],
                        'name' => ['value' => 'transaction_name'],
                    ],
                ],
            ],
        ]);

        $this->assertCount(2, $result);

        $account = $result[0][0];
        $t1 = $result[0][1];
        $t2 = $result[1][1];

        $this->assertSame($result[1][0], $account);
        $this->assertInstanceOf(Account::class, $account);
        $this->assertInstanceOf(Transaction::class, $t1);
        $this->assertInstanceOf(Transaction::class, $t2);

        $this->assertSame('Test account', $account->getName());
        $this->assertSame('000001', $account->getCode());

        $this->assertSame('Transaction 1', $t1->getName());
        $this->assertSame('Transaction 2', $t2->getName());

        $this->assertSame($account, $t1->getAccount());
        $this->assertSame($account, $t2->getAccount());
    }

    /**
     * Test processor exceptions.
     *
     * @test
     * @functional
     *
     * @return void
     * @throws Throwable
     */
    public function testExceptions(): void
    {
        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                ['name' => 'Test']
            );
        } catch (MissingConfigurationException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Missing configuration "data-map" for App\Import\Processor\DataMapProcessor.',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                new stdClass(),
                [DataMapProcessor::OPTION_DATA_MAP => []]
            );
        } catch (InputNotSupportedException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Given input of type "object" not supported by "App\Import\Processor\DataMapProcessor".',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                ['test' => 'name'],
                [
                    DataMapProcessor::OPTION_DATA_MAP => [
                        Account::class => [
                            'map' => [
                                'name' => ['value' => 'name'],
                            ],
                        ],
                    ],
                ]
            );
        } catch (MissingFieldException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Input "name" field missing for item "App\Entity\Account\Account", field "name". Existing: "test".',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                ['name' => 'test'],
                [
                    DataMapProcessor::OPTION_DATA_MAP => [
                        Account::class => [
                            'parser' => [],
                        ],
                    ],
                ]
            );
        } catch (MissingParserClassException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Parser class missing in data map for item "App\Entity\Account\Account".',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                ['name' => 'test'],
                [
                    DataMapProcessor::OPTION_DATA_MAP => [
                        Account::class => [
                            'map' => [
                                'name' => [
                                    'parser' => [],
                                    'value' => 'name',
                                ]
                            ]
                        ],
                    ],
                ]
            );
        } catch (MissingParserClassException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Parser class missing in data map for item "App\Entity\Account\Account", field "name".',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                ['name' => 'test'],
                [
                    DataMapProcessor::OPTION_DATA_MAP => [
                        Account::class => [
                            'parser' => ['class' => '__Unknown'],
                        ],
                    ],
                ]
            );
        } catch (ParserClassException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Class "__Unknown" does not exists for item "App\Entity\Account\Account".',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                ['name' => 'test'],
                [
                    DataMapProcessor::OPTION_DATA_MAP => [
                        Account::class => [
                            'map' => [
                                'name' => [
                                    'parser' => ['class' => '__Unknown'],
                                    'value' => 'name'
                                ]
                            ]
                        ],
                    ],
                ]
            );
        } catch (ParserClassException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Class "__Unknown" does not exists for item "App\Entity\Account\Account", field "name".',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                ['name' => 'test'],
                [
                    DataMapProcessor::OPTION_DATA_MAP => [
                        '__Unknown' => [],
                    ],
                ]
            );
        } catch (ItemClassException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Class "__Unknown" for item "__Unknown" does not exist.',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                ['name' => 'test'],
                [
                    DataMapProcessor::OPTION_DATA_MAP => [
                        Transaction::class => [
                            'map' => [
                                'account' => ['reference' => 'account.00001'],
                            ],
                        ],
                    ],
                ]
            );
        } catch (IdentifierNotFoundException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Identifier for item account "00001" not found in data map.',
            $e->getMessage()
        );

        $e = null;
        try {
            (new DataMapProcessor(static::getContainer()))->process(
                [['code' => '0001']],
                [
                    DataMapProcessor::OPTION_DATA_MAP => [
                        Account::class => [
                            'identifier' => 'none',
                        ],
                    ],
                ]
            );
        } catch (NoSuchPropertyException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            "Can't get a way to read the property \"none\" in class \"App\Entity\Account\Account\".",
            $e->getMessage()
        );
    }

}

/**
 * Fake parser for item parsing test.
 *
 * @internal
 */
class FakeItemParser implements ParserInterface
{

    /**
     * {@inheritDoc}
     */
    public function isSupported(mixed $value): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function parse(mixed $value): Transaction
    {
        $transaction = new Transaction();

        $transaction->setName($value['name']);

        return $transaction;
    }

}

/**
 * Input test as iterator.
 */
class TestInput implements Iterator
{

    /**
     * Iterator index.
     *
     * @var int
     */
    private int $index = 0;

    /**
     * {@inheritDoc}
     */
    public function current(): string
    {
        return '000001';
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * {@inheritDoc}
     */
    public function key(): string
    {
        return 'name';
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return $this->index === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

}