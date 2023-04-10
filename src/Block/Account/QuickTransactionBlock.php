<?php

namespace App\Block\Account;

use App\Block\BlockServiceInterface;
use App\Block\EditableBlockService;
use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Form\Account\Type\QuickTransactionType;
use App\Repository\Account\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Meta\MetadataInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Type\ImmutableArrayType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Twig\Environment;

/**
 * Quick add transaction to configured account block.
 */
final class QuickTransactionBlock extends AbstractBlockService implements
    BlockServiceInterface,
    EditableBlockService
{

    /**
     * @param Environment            $twig              Twig environment.
     * @param AccountRepository      $accountRepository Account repository.
     * @param FormFactory            $formFactory       Form factory.
     * @param RequestStack           $requestStack      Request stack.
     * @param EntityManagerInterface $manager           Entity manager.
     */
    public function __construct(
        Environment $twig,
        private readonly AccountRepository $accountRepository,
        private readonly FormFactoryInterface $formFactory,
        private readonly RequestStack $requestStack,
        private readonly EntityManagerInterface $manager,
    ) {
        parent::__construct($twig);
    }

    /**
     * {@inheritDoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'account' => null,
            'title' => 'Account.block.quick_transaction.title',
            'template' => 'block/Account/quick_transaction.block.html.twig',
        ]);
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
    public function configureCreateForm(
        FormMapper $form,
        BlockInterface $block
    ): void {
        $this->configureCreateForm($form, $block);
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
        $form = null;

        if ($account) {
            $form = $this->getForm($account);
            $form->handleRequest($this->requestStack->getCurrentRequest());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->manager->persist($form->getData());
                $this->manager->flush();

                $form = $this->getForm($account);
            }
        }

        return $this->renderResponse($template, [
            'account' => $account,
            'block' => $blockContext->getBlock(),
            'form' => $form?->createView(),
            'settings' => $settings,
        ]);
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
     * {@inheritDoc}
     */
    public function getMetadata(): MetadataInterface
    {
        return new Metadata(
            'app.block.account.quick_transaction',
            null,
            null,
            'App',
            [
                'class' => 'fa fa-dollar',
            ]
        );
    }

    /**
     * Get form.
     *
     * @param Account $account Transaction account.
     *
     * @return FormInterface
     */
    private function getForm(Account $account): FormInterface
    {
        $transaction = new Transaction();
        $transaction->setAccount($account);

        return $this->formFactory->create(
            QuickTransactionType::class,
            $transaction,
        );
    }
}