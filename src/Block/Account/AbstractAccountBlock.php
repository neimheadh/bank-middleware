<?php

namespace App\Block\Account;

use App\Block\BlockServiceInterface;
use App\Block\EditableBlockService;
use App\Entity\Account\Account;
use App\Repository\Account\AccountRepository;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Meta\MetadataInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Type\ImmutableArrayType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Twig\Environment;

abstract class AbstractAccountBlock extends AbstractBlockService implements
    BlockServiceInterface,
    EditableBlockService
{

    /**
     * @param Environment       $twig              Twig environment.
     * @param AccountRepository $accountRepository Account repository.
     */
    public function __construct(
        Environment $twig,
        private readonly AccountRepository $accountRepository,
    ) {
        parent::__construct($twig);
    }

    /**
     * {@inheritDoc}
     */
    public function configureCreateForm(
        FormMapper $form,
        BlockInterface $block
    ): void {
        $this->configureCreateForm($form, $block);
    }

    /**
     * {@inheritDoc}
     */
    public function configureEditForm(
        FormMapper $form,
        BlockInterface $block
    ): void {
        $form->add('settings', ImmutableArrayType::class, [
            'keys' => [
                [
                    'account',
                    EntityType::class,
                    [
                        'class' => Account::class,
                    ],
                ],
            ],
            'translation_domain' => 'admin',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'account' => null,
            'title' => $this->getTitle(),
            'template' => $this->getTemplate(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(
        BlockContextInterface $blockContext,
        ?Response $response = null
    ): Response {
        $settings = $blockContext->getSettings();
        $template = $blockContext->getTemplate();
        assert(null !== $template);

        $account = $settings['account']
            ? $this->accountRepository->find($settings['account'])
            : null;

        return $this->renderResponse($template, [
            'account' => $account,
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata(): MetadataInterface
    {
        return new Metadata(
            $this->getTitle(),
            null,
            null,
            'App',
            [
                'class' => 'fa fa-dollar',
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function validate(
        ErrorElement $errorElement,
        BlockInterface $block
    ): void {
        $errorElement->with('settings[account]')
            ->addConstraint(new NotNull())
            ->addConstraint(new NotBlank())
            ->end();
    }

    /**
     * Get block template.
     *
     * @return string
     */
    abstract protected function getTemplate(): string;

    /**
     * Get block title.
     *
     * @return string
     */
    abstract protected function getTitle(): string;

}