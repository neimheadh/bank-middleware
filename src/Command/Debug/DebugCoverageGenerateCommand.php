<?php

namespace App\Command\Debug;

use SebastianBergmann\PHPCOV\ArgumentsBuilder;
use SebastianBergmann\PHPCOV\MergeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate coverage report command.
 */
#[AsCommand(
    name: 'app:debug:coverage:generate',
    description: 'Generate coverage report from coverage files.',
    aliases: ['app:debug:coverage'],
)]
class DebugCoverageGenerateCommand extends DebugCoverageCommand
{

    /**
     * Output directory argument key.
     */
    public const ARG_OUTPUT_DIRECTORY = 'output-directory';

    /**
     * Coverage directory option key.
     */
    public const OPT_COVERAGE_DIRECTORY = 'coverage-directory';

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->addArgument(
                self::ARG_OUTPUT_DIRECTORY,
                InputArgument::REQUIRED,
                'Report output directory',
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $phpCov = new MergeCommand();
        $coverageDirectory = $this->getCoverageDir($input);
        $outputDirectory = $input->getArgument(self::ARG_OUTPUT_DIRECTORY);

        $arguments = (new ArgumentsBuilder())->build([
            null,
            'merge',
            '--html',
            $outputDirectory,
            $coverageDirectory,
        ]);

        $phpCov->run($arguments);

        return self::SUCCESS;
    }

}