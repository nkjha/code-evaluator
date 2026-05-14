<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Submission;
use App\Jobs\EvaluateSubmissionJob;

class SubmissionController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([

            'challenge_id' => 'required',

            'language' => 'required',

            'code' => 'required'
        ]);

        $submission = Submission::create([

            'user_id' => 1,

            'challenge_id' => $request->challenge_id,

            'language' => $request->language,

            'code' => $request->code,

            'status' => 'queued'
        ]);

        EvaluateSubmissionJob::dispatch($submission);

        return response()->json([

            'success' => true,

            'submission_id' => $submission->id,

            'message' => 'Code submitted successfully'
        ]);
    }

    public function status(Submission $submission)
    {
        return response()->json([
            'success' => true,
            'submission_id' => $submission->id,
            'status' => $submission->status,
            'challenge_id' => $submission->challenge_id,
            'language' => $submission->language,
            'total_testcases' => $submission->total_testcases ?? null,
            'passed_testcases' => $submission->passed_testcases ?? null,
            'score' => $submission->score ?? null,
            'created_at' => $submission->created_at,
            'updated_at' => $submission->updated_at,
        ]);
    }
}