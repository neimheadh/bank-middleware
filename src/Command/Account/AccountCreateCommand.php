<?php

namespace App\Command\Account;

use App\Entity\Account\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Account creation command.
 */
#[AsCommand(
  name: "app:account:create",
  description: "Create an account."
)]
class AccountCreateCommand extends Command
{

    /**
     * @param EntityManagerInterface $em Entity manager.
     */
    public function __construct(
      private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->addArgument(
          'name',
          InputArgument::REQUIRED,
          'Account name.',
        )->addArgument(
          'balance',
          InputArgument::OPTIONAL,
          'Account balance.',
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(
      InputInterface $input,
      OutputInterface $output
    ): int {
        $name = $input->getArgument('name');
        $balance = floatval($input->getArgument('balance')) ?: 0.0;

        $account = new Account();
        $account->setName($name);
        $account->setBalance($balance);

        $this->em->persist($account);
        $this->em->flush();

        $output->writeln(
          sprintf(
            '<info>Account #%s created</info>',
            $account->getId(),
          )
        );

        return self::SUCCESS;
    }

}