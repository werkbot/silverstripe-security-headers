<?php

namespace Signify\Tasks;

use Override;
use Signify\Jobs\RemoveUnreferencedCSPDocumentJob;
use SilverStripe\Dev\BuildTask;
use SilverStripe\PolyExecution\PolyOutput;
use Symbiote\QueuedJobs\Services\QueuedJobService;
use Symfony\Component\Console\Input\InputInterface;

class RemoveUnreferencedCSPDocumentsTask extends BuildTask
{
    protected string $title = 'Remove unreferenced CSP Document URIs';

    protected static string $description =
    'CSP Document URIs that are not referenced by a CSP violation report can be safely removed.';

    /**
     * {@inheritDoc}
     * @see \SilverStripe\Dev\BuildTask::run()
     */
    public function execute(InputInterface $input, PolyOutput $output): int
    {
        $deletionJob = new RemoveUnreferencedCSPDocumentJob();

        $jobId = singleton(QueuedJobService::class)->queueJob($deletionJob);

        print "Job queued with ID $jobId\n";
    }

    #[Override]
    public function isEnabled(): bool
    {
        return parent::isEnabled() && class_exists(QueuedJobService::class);
    }
}
