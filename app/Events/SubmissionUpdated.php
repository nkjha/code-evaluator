<?php

namespace App\Events;

use App\Models\Submission;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubmissionUpdated implements ShouldBroadcast
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public $submission;

    public function __construct($submission)
    {
        $this->submission = $submission;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('submission-channel')
        ];
    }

    public function broadcastAs(): string
    {
        return 'submission.updated';
    }
}