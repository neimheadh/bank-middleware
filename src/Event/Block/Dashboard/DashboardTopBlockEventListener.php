<?php

namespace App\Event\Block\Dashboard;

use App\Repository\Block\BlockRepository;
use Sonata\BlockBundle\Event\BlockEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Dashboard top block event listener.
 */
#[AsEventListener(event: 'sonata.block.event.sonata.admin.dashboard.top')]
class DashboardTopBlockEventListener
{

    public function __construct(
        private BlockRepository $blockRepository
    ) {
    }

    /**
     * Handle block event.
     *
     * @param BlockEvent $event Block event.
     *
     * @return void
     */
    public function __invoke(BlockEvent $event): void
    {
        foreach ($this->blockRepository->findByType('dashboard') as $block) {
            $event->addBlock($block->getSonataBlock());
        }
    }

}