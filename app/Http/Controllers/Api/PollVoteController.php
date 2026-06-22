<?php

namespace App\Http\Controllers\Api;

use App\Actions\Polls\CastVoteAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Polls\CastVoteRequest;
use App\Models\Poll;
use App\Queries\Polls\GetPollResultsQuery;
use Illuminate\Http\JsonResponse;

class PollVoteController extends Controller
{
    public function __invoke(
        Poll $poll,
        CastVoteRequest $request,
        CastVoteAction $castVote,
        GetPollResultsQuery $pollResults,
    ): JsonResponse {
        $castVote->execute($poll, $request->pollOptionId());

        return response()->json([
            'data' => $pollResults->execute($poll->refresh()),
        ], 201);
    }
}
