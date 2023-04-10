<?php

namespace App\Event\Block\Dashboard;

use App\Entity\Block\Block;
use App\Repository\Block\BlockRepository;
use Sonata\BlockBundle\Event\BlockEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Dashboard top block event listener.
 */
#[AsEventListener(event: 'sonata.block.event.sonata.admin.dashboard.top', method: 'onTop')]
#[AsEventListener(event: 'sonata.block.event.sonata.admin.dashboard.left', method: 'onLeft')]
#[AsEventListener(event: 'sonata.block.event.sonata.admin.dashboard.center', method: 'onCenter')]
#[AsEventListener(event: 'sonata.block.event.sonata.admin.dashboard.right', method: 'onRight')]
#[AsEventListener(event: 'sonata.block.event.sonata.admin.dashboard.bottom', method: 'onBottom')]
class DashboardBlockEventListener
{

    public function __construct(
        private BlockRepository $blockRepository
    ) {
    }

    /**
     * Handle bottom block event.
     *
     * @param BlockEvent $event Block event.
     *
     * @return void
     */
    public function onBottom(BlockEvent $event): void
    {
        $this->addBlocks($event, Block::POSITION_BOTTOM);
    }

    /**
     * Handle center block event.
     *
     * @param BlockEvent $event Block event.
     *
     * @return void
     */
    public function onCenter(BlockEvent $event): void
    {
        $this->addBlocks($event, Block::POSITION_CENTER);
    }

    /**
     * Handle left block event.
     *
     * @param BlockEvent $event Block event.
     *
     * @return void
     */
    public function onLeft(BlockEvent $event): void
    {
        $this->addBlocks($event, Block::POSITION_LEFT);
    }

    /**
     * Handle right block event.
     *
     * @param BlockEvent $event Block event.
     *
     * @return void
     */
    public function onRight(BlockEvent $event): void
    {
        $this->addBlocks($event, Block::POSITION_RIGHT);
    }

    /**
     * Handle top block event.
     *
     * @param BlockEvent $event Block event.
     *
     * @return void
     */
    public function onTop(BlockEvent $event): void
    {
        $this->addBlocks($event, Block::POSITION_TOP);
    }

    /**
     * Add dashboard blocks.
     *
     * @param BlockEvent $event    Block event.
     * @param int        $position Blocks position.
     *
     * @return void
     */
    private function addBlocks(BlockEvent $event, int $position): void
    {
        foreach ($this->blockRepository->findByTypeAndPosition(
            Block::TYPE_DASHBOARD,
            $position
        ) as $block) {
            $event->addBlock($block->getSonataBlock());
        }
    }

}