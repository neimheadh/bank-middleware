<?php

namespace App\DataFixtures\Block;

use App\Block\Account\QuickTransactionBlock;
use App\DataFixtures\Account\AccountFixture;
use App\Entity\Block\Block;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Block fixtures loader.
 */
class BlockFixture extends Fixture implements DependentFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            AccountFixture::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $block = new Block();
        $block->setClass(QuickTransactionBlock::class);
        $block->setType(Block::TYPE_DASHBOARD);
        $block->setPosition(Block::POSITION_TOP);
        $block->setSettings([
            'account' => $this->getReference(
                AccountFixture::TEST_ACCOUNT_REFERENCE,
            )->getId(),
        ]);
        $manager->persist($block);

        $manager->flush();
    }

}