<?php

namespace App\Event\Kernel\Debugging;

use Exception;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\PHP as Report;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Enable xdebug code coverage.
 */
#[AsEventListener(event: 'kernel.request', method: 'onKernelRequest')]
#[AsEventListener(event: 'kernel.terminate', method: 'onKernelTerminate')]
class XDebugCoverageEnabler
{

    /**
     * Code coverage object.
     *
     * Initialized by kernel request event.
     *
     * @var CodeCoverage|null
     */
    private ?CodeCoverage $coverage = null;

    /**
     * @param bool $debugCoverage Is xdebug coverage enabled.
     */
    public function __construct(
        private readonly bool $debugCoverage,
        private readonly string $coverageDir,
        private readonly string $projectDir,
    ) {
    }

    /**
     * Enable debug coverage if necessary.
     *
     * @param RequestEvent $event Request event.
     *
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->debugCoverage && extension_loaded('xdebug')) {
            $filter = new Filter();
            $filter->includeDirectory($this->projectDir . '/src');

            $this->coverage = new CodeCoverage(
                (new Selector())->forLineCoverage($filter),
                $filter,
            );
            $this->coverage->start($event->getRequest()->getUri());
        }
    }

    /**
     * Finish code coverage and save result.
     *
     * @return void
     * @throws Exception Error on report file generation.
     */
    public function onKernelTerminate(): void
    {
        if ($this->coverage) {
            // We create the coverage directory if necessary.
            if (!is_dir($this->coverageDir)) {
                mkdir($this->coverageDir, 0777, true);
            }

            $this->coverage->stop();
            (new Report())->process(
                $this->coverage,
                sprintf(
                    '%s%s%s.cov',
                    $this->coverageDir,
                    DIRECTORY_SEPARATOR,
                    bin2hex(random_bytes(16)),
                ),
            );

            $this->coverage->clear();
            $this->coverage = null;
        }
    }
}