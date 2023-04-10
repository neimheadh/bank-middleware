<?php

namespace App\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\BlockServiceInterface as Base;
use Symfony\Component\HttpFoundation\Response;

/**
 * Block service.
 */
interface BlockServiceInterface extends Base
{

    /**
     * Execute block.
     *
     * @param BlockContextInterface $blockContext Block context.
     * @param Response|null         $response     Response created by the block
     *                                            renderer.
     *
     * @return Response
     */
    public function execute(
        BlockContextInterface $blockContext,
        ?Response $response = null
    ): Response;

}