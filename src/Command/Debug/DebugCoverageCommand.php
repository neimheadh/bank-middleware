<?php

namespace App\Command\Debug;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Debug coverage commands main class.
 */
abstract class DebugCoverageCommand extends Command
{

    /**
     * Coverage directory option key.
     */
    public const OPT_COVERAGE_DIRECTORY = 'coverage-directory';

    /**
     * @param string $coverageDir Application coverage directory.
     */
    public function __construct(
        private readonly string $coverageDir,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->addOption(
                self::OPT_COVERAGE_DIRECTORY,
                null,
                InputOption::VALUE_REQUIRED,
                'Coverage files directory.',
                $this->coverageDir,
            );
    }

    /**
     * Get coverage directory.
     *
     * @param InputInterface $input Console input.
     *
     * @return string
     */
    protected function getCoverageDir(InputInterface $input): string
    {
        $coverageDirectory = $input->getOption(
            self::OPT_COVERAGE_DIRECTORY,
        );

        if (!is_dir($coverageDirectory)) {
            throw new RuntimeException(
                sprintf(
                    'Directory "%s" does not exist.',
                    $coverageDirectory,
                )
            );
        }

        return $coverageDirectory;
    }

}