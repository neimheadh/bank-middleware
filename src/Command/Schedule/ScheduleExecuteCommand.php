<?php

namespace App\Command\Schedule;

use App\Entity\Schedule\TransactionSchedule;
use App\Model\Entity\Schedule\ScheduleEntityInterface;
use App\Model\Repository\Schedule\ScheduleEntityRepositoryInterface;
use App\Repository\Schedule\TransactionScheduleRepository;
use App\Schedule\Generator\ScheduleGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Execute schedule command.
 */
#[AsCommand(
    name: 'app:schedule:execute',
    description: 'Execute scheduled actions.',
)]
final class ScheduleExecuteCommand extends Command
{

    /**
     * @param TransactionScheduleRepository $transactions Transaction schedule
     *                                                    repository.
     * @param ContainerInterface            $container    Application
     *                                                    container.
     * @param EntityManagerInterface        $manager      Doctrine entity
     *                                                    manager.
     */
    public function __construct(
        private readonly TransactionScheduleRepository $transactions,
        private readonly ContainerInterface $container,
        private readonly EntityManagerInterface $manager,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        /** @var array<string, ScheduleEntityRepositoryInterface> $scheduled */
        $scheduled = [
            'transactions' => $this->transactions,
        ];

        foreach ($scheduled as $name => $repository) {
            $items = $repository->findScheduled();
            $output->writeln(
                sprintf(
                    '<comments>%s</comments> %s.',
                    count($items),
                    $name
                )
            );
            $progress = new ProgressBar($output, count($items));
            foreach ($items as $item) {
                /** @var ScheduleGeneratorInterface $generator */
                $generator = $this->container->get($item->getGeneratorClass());

                if ($generator->isScheduled($item)) {
                    $this->manager->persist($generator->generate($item));
                    $this->manager->flush();
                }

                $progress->advance();
            }
            $progress->finish();
            $output->writeln('');
        }

        return self::SUCCESS;
    }

}