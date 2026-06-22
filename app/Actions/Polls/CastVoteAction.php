<?php

namespace App\Actions\Polls;

use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Validation\ValidationException;

class CastVoteAction
{
    public function execute(Poll $poll, string $pollOptionId): Vote
    {
        $optionBelongsToPoll = $poll->options()
            ->whereKey($pollOptionId)
            ->exists();

        if (! $optionBelongsToPoll) {
            throw ValidationException::withMessages([
                Vote::POLL_OPTION_ID => 'The selected option does not belong to this poll.',
            ]);
        }

        return $poll->votes()->create([
            Vote::POLL_OPTION_ID => $pollOptionId,
        ]);
    }
}
