<?php

namespace App\Block\Account;

use App\Block\BlockServiceInterface;
use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Form\Account\Type\QuickTransactionType;
use App\Repository\Account\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Meta\MetadataInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Twig\Environment;

/**
 * Quick add transaction to configured account block.
 */
final class QuickTransactionBlock extends AbstractAccountBlock implements
    BlockServiceInterface
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
        parent::__construct($twig, $this->accountRepository);
    }

    /**
     * {@inheritDoc}
     */
    protected function getTemplate(): string
    {
        return 'block/Account/quick_transaction.block.html.twig';
    }

    /**
     * {@inheritDoc}
     */
    protected function getTitle(): string
    {
        return 'Account.block.quick_transaction.title';
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