<?php

namespace App\Actions\Polls;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Support\Facades\DB;

class CreatePollAction
{
    /**
     * @param  list<string>  $optionTexts
     */
    public function execute(string $question, array $optionTexts): Poll
    {
        return DB::transaction(function () use ($question, $optionTexts): Poll {
            $poll = Poll::create([
                Poll::QUESTION => $question,
            ]);

            foreach ($optionTexts as $optionText) {
                $poll->options()->create([
                    PollOption::TEXT => $optionText,
                ]);
            }

            return $poll->load('options');
        });
    }
}
