<?php

namespace App\Block;

use Sonata\BlockBundle\Block\Service\EditableBlockService as Base;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Meta\MetadataInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Validator\ErrorElement;

/**
 * Editable block service.
 */
interface EditableBlockService extends Base
{

    /**
     * Configure block edition form.
     *
     * @param FormMapper     $form  Edition form mapper.
     * @param BlockInterface $block Edited block.
     *
     * @return void
     */
    public function configureEditForm(
        FormMapper $form,
        BlockInterface $block
    ): void;

    /**
     * Configure block creation form.
     *
     * @param FormMapper     $form  Creation form mapper.
     * @param BlockInterface $block Created block.
     *
     * @return void
     */
    public function configureCreateForm(
        FormMapper $form,
        BlockInterface $block
    ): void;

    /**
     * Configure block validation.
     *
     * @param ErrorElement   $errorElement Error validation tree.
     * @param BlockInterface $block        Validated block.
     *
     * @return void
     */
    public function validate(
        ErrorElement $errorElement,
        BlockInterface $block
    ): void;

    /**
     * Get block metadatas.
     *
     * @return MetadataInterface
     */
    public function getMetadata(): MetadataInterface;

}