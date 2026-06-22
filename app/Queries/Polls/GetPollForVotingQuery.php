<?php

namespace App\Queries\Polls;

use App\Models\Poll;
use App\Models\PollOption;

class GetPollForVotingQuery
{
    public const OPTIONS = 'options';

    /**
     * @return array{id: string, question: string, options: list<array{id: string, text: string}>}
     */
    public function execute(Poll $poll): array
    {
        $options = $poll->options()->oldest()->get();

        return [
            Poll::ID => (string) $poll->getKey(),
            Poll::QUESTION => (string) $poll->getAttribute(Poll::QUESTION),
            self::OPTIONS => $options
                ->map(fn (PollOption $option): array => [
                    PollOption::ID => (string) $option->getKey(),
                    PollOption::TEXT => (string) $option->getAttribute(PollOption::TEXT),
                ])
                ->values()
                ->all(),
        ];
    }
}
