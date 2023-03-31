<?php

namespace App\Command\Import;

use App\Import\Processor\ProcessorInterface;
use App\Import\Reader\ReaderInterface;
use App\Import\Writer\WriterInterface;
use App\Repository\Import\ProfileRepository;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Import file command.
 */
#[AsCommand(
    name: 'app:import:file',
    description: 'Import file',
    aliases: ['app:import']
)]
class ImportFileCommand extends Command
{

    /**
     * File argument name.
     */
    public const ARGUMENT_FILE = 'file';

    /**
     * Profile argument name.
     */
    public const ARGUMENT_PROFILE = 'profile';

    /**
     * @param ProfileRepository  $profileRepository Import profile repository.
     * @param ContainerInterface $container         Symfony container.
     */
    public function __construct(
        private readonly ProfileRepository $profileRepository,
        private readonly ContainerInterface $container,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->addArgument(
            self::ARGUMENT_PROFILE,
            InputArgument::REQUIRED,
            'Import profile code.'
        )->addArgument(
            self::ARGUMENT_FILE,
            InputArgument::REQUIRED,
            'Imported file.'
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $profileCode = $input->getArgument(self::ARGUMENT_PROFILE);
        $file = $input->getArgument(self::ARGUMENT_FILE);
        $progress = new ProgressBar($output);
        $profile = $this->profileRepository->findOneByCode($profileCode);

        if ($profile === null) {
            throw new InvalidArgumentException(
                sprintf('Cannot find profile with code "%s".', $profileCode)
            );
        }

        /** @var ReaderInterface $reader */
        $reader = $this->container->get($profile->getReader());
        /** @var ProcessorInterface $processor */
        $processor = $this->container->get($profile->getProcessor());
        /** @var WriterInterface $writer */
        $writer = $this->container->get($profile->getWriter());

        $read = $reader->read($file, array_merge(
            ['progress' => $progress],
            $profile->getReaderConfiguration(),
        ));
        $process = $processor->process(
            $read,
            $profile->getProcessorConfiguration()
        );
        $writer->write($process, $profile->getWriterConfiguration());
        $output->writeln("\n");

        return self::SUCCESS;
    }

}