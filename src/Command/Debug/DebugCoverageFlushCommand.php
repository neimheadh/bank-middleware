<?php

namespace App\Command\Debug;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clear coverage reports command.
 */
#[AsCommand(
    name: 'app:debug:coverage:flush',
    description: 'Clear coverage reports.',
)]
class DebugCoverageFlushCommand extends DebugCoverageCommand
{

    /**
     * {@inheritDoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $coverageDir = $this->getCoverageDir($input);

        $files = array_values(
            array_filter(
                scandir($coverageDir),
                fn(string $file) => is_file(
                        $coverageDir . DIRECTORY_SEPARATOR . $file
                    )
                    && pathinfo($file, PATHINFO_EXTENSION) === 'cov',
            )
        );

        $output->writeln('<info>Clean coverage report files:</info>');
        $progress = new ProgressBar($output, count($files));
        $progress->start();
        foreach ($files as $file) {
            $progress->advance();
            unlink($coverageDir . DIRECTORY_SEPARATOR . $file);
        }
        $progress->finish();

        $output->writeln(
            sprintf(
                "\n<info><comment>%s</comment> files deleted.</info>",
                count($files),
            ),
        );

        return self::SUCCESS;
    }

}