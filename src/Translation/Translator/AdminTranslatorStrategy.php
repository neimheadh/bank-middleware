<?php

namespace App\Translation\Translator;

use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Translator\LabelTranslatorStrategyInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Admin label translation strategy.
 */
class AdminTranslatorStrategy implements LabelTranslatorStrategyInterface
{

    /**
     * @param TranslatorInterface $translator   Translator.
     * @param RequestStack        $requestStack Request stack.
     */
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel(
        string $label,
        string $context = '',
        string $type = ''
    ): string {
        $request = $this->requestStack->getCurrentRequest();
        $admin = preg_replace(
            '/^app\\.admin\\./',
            '',
            $request->attributes->get('_sonata_admin')
        );

        return $this->translator->trans(
            "$admin.$context.$type.$label",
            domain: 'admin',
        );
    }

}