<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Models\TestCase;

use App\Services\CodeExecutorService;

use App\Events\SubmissionUpdated;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EvaluateSubmissionJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $submission;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $submission = $this->submission;

        $submission->update([
            'status' => 'running'
        ]);

        $testCases = TestCase::where(
            'challenge_id',
            $submission->challenge_id
        )->get();

        $passed = 0;

        foreach ($testCases as $testCase) {

            $output = CodeExecutorService::execute(

                $submission->language,

                $submission->code,

                $testCase->input
            );

            if (
                trim($output) ==
                trim($testCase->expected_output)
            ) {
                $passed++;
            }
        }

        $score = 0;

        if (count($testCases) > 0) {

            $score = (
                $passed /
                count($testCases)
            ) * 100;
        }

        $submission->update([

            'total_testcases' => count($testCases),

            'passed_testcases' => $passed,

            'score' => $score,

            'status' => 'completed'
        ]);

        // Broadcast Event
        event(new SubmissionUpdated($submission));
    }

    /**
     * Failed Job
     */
    public function failed(\Throwable $exception): void
    {
        $this->submission->update([

            'status' => 'failed',

            'error' => $exception->getMessage()
        ]);
    }

    /**
     * Retry delays
     */
    public function backoff(): array
    {
        return [10, 30, 60];
    }
}