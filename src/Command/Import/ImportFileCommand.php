<?php

namespace App\Command\Import;

use App\Entity\Import\Profile;
use App\Import\Configuration\ConfigurationReader;
use App\Import\Processor\ProcessorInterface;
use App\Import\Reader\ReaderInterface;
use App\Import\Result\ResultCollection;
use App\Import\Writer\WriterInterface;
use App\Repository\Import\ProfileRepository;
use InvalidArgumentException;
use SplFileObject;
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
    description: 'Execute an import.',
    aliases: ['app:import']
)]
final class ImportFileCommand extends Command
{

    /**
     * File argument.
     */
    public const ARG_FILE = 'file';

    /**
     * Profile argument.
     */
    public const ARG_PROFILE = 'profile';

    /**
     * {@inheritDoc}
     *
     * @param ConfigurationReader $configurationReader Import configuration
     *                                                 reader.
     * @param ProfileRepository   $profileRepository   Import profile
     *                                                 repository.
     * @param ContainerInterface  $container           Application container.
     */
    public function __construct(
        private readonly ConfigurationReader $configurationReader,
        private readonly ProfileRepository $profileRepository,
        private readonly ContainerInterface $container,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addArgument(
            self::ARG_PROFILE,
            InputArgument::REQUIRED,
            'Import profile code.'
        )->addArgument(
            self::ARG_FILE,
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
        $profile = $this->getProfile($input);
        $file = new SplFileObject($input->getArgument(self::ARG_FILE), 'r');
        $progress = new ProgressBar($output);

        /** @var ReaderInterface $reader */
        $reader = $this->container->get($profile->getReader());
        /** @var ProcessorInterface $processor */
        $processor = $this->container->get($profile->getProcessor());
        /** @var WriterInterface $writer */
        $writer = $this->container->get($profile->getWriter());

        $config = $profile->getConfiguration()
            ? $this->configurationReader->readYaml(
                $profile->getConfiguration()
            ) : null;

        $result = $reader->read($file, $config);

        // We create a new result to connect the progress bar.
        $progress->start($result->count());
        $result = ResultCollection::from(
            $result,
            next: function () use ($result, $progress) {
                $result->next();
                $progress->advance();
            }
        );

        $result = $processor->process($result, $config);

        // Loop on all result to write everything.
        iterator_to_array($writer->write($result, $config));
        $progress->finish();;
        $output->writeln('');

        return self::SUCCESS;
    }

    /**
     * Get profile from command input.
     *
     * @param InputInterface $input Command input.
     *
     * @return Profile
     */
    private function getProfile(InputInterface $input): Profile
    {
        $code = $input->getArgument(self::ARG_PROFILE);
        $profile = $this->profileRepository->findOneByCode($code);

        if ($profile === null) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot find profile with code "%s".',
                    $code
                )
            );
        }

        return $profile;
    }

}