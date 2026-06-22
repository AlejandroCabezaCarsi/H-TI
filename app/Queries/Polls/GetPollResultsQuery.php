<?php

namespace App\Queries\Polls;

use App\Models\Poll;
use App\Models\PollOption;

class GetPollResultsQuery
{
    public const OPTIONS = 'options';

    public const PERCENTAGE = 'percentage';

    public const TOTAL_VOTES = 'total_votes';

    public const VOTES_COUNT = 'votes_count';

    /**
     * @return array{id: string, question: string, total_votes: int, options: list<array{id: string, text: string, votes_count: int, percentage: float}>}
     */
    public function execute(Poll $poll): array
    {
        $options = $poll->options()
            ->withCount('votes')
            ->oldest()
            ->get();

        $totalVotes = (int) $options->sum(self::VOTES_COUNT);

        return [
            Poll::ID => (string) $poll->getKey(),
            Poll::QUESTION => (string) $poll->getAttribute(Poll::QUESTION),
            self::TOTAL_VOTES => $totalVotes,
            self::OPTIONS => $options
                ->map(fn (PollOption $option): array => $this->formatOption($option, $totalVotes))
                ->values()
                ->all(),
        ];
    }

    /**
     * @return array{id: string, text: string, votes_count: int, percentage: float}
     */
    private function formatOption(PollOption $option, int $totalVotes): array
    {
        $votesCount = (int) $option->getAttribute(self::VOTES_COUNT);

        return [
            PollOption::ID => (string) $option->getKey(),
            PollOption::TEXT => (string) $option->getAttribute(PollOption::TEXT),
            self::VOTES_COUNT => $votesCount,
            self::PERCENTAGE => $totalVotes === 0 ? 0.0 : round(($votesCount / $totalVotes) * 100, 1),
        ];
    }
}
