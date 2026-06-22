<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PollApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_vote_and_view_poll_results(): void
    {
        $createResponse = $this->postJson('/api/polls', [
            Poll::QUESTION => 'Where should we have lunch?',
            'options' => ['Pizza', 'Sushi', 'Burgers'],
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.question', 'Where should we have lunch?')
            ->assertJsonCount(3, 'data.options');

        $pollId = $createResponse->json('data.id');
        $optionId = $createResponse->json('data.options.0.id');

        $this->getJson("/api/polls/{$pollId}")
            ->assertOk()
            ->assertJsonPath('data.id', $pollId);

        $this->postJson("/api/polls/{$pollId}/votes", [
            Vote::POLL_OPTION_ID => $optionId,
        ])
            ->assertCreated()
            ->assertJsonPath('data.total_votes', 1)
            ->assertJsonPath('data.options.0.votes_count', 1)
            ->assertJsonPath('data.options.0.percentage', 100);

        $this->getJson("/api/polls/{$pollId}/results")
            ->assertOk()
            ->assertJsonPath('data.total_votes', 1);
    }

    public function test_vote_option_must_belong_to_poll(): void
    {
        $poll = Poll::create([
            Poll::QUESTION => 'Choose a frontend framework',
        ]);
        $otherPoll = Poll::create([
            Poll::QUESTION => 'Choose a backend framework',
        ]);
        $foreignOption = $otherPoll->options()->create([
            PollOption::TEXT => 'Laravel',
        ]);

        $this->postJson("/api/polls/{$poll->getKey()}/votes", [
            Vote::POLL_OPTION_ID => $foreignOption->getKey(),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(Vote::POLL_OPTION_ID);
    }
}
