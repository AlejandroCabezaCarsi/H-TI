<?php

namespace App\Http\Controllers\Api;

use App\Actions\Polls\CreatePollAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Polls\StorePollRequest;
use App\Models\Poll;
use App\Queries\Polls\GetPollForVotingQuery;
use Illuminate\Http\JsonResponse;

class PollController extends Controller
{
    public function store(
        StorePollRequest $request,
        CreatePollAction $createPoll,
        GetPollForVotingQuery $pollForVoting,
    ): JsonResponse {
        $poll = $createPoll->execute($request->pollQuestion(), $request->optionTexts());

        return response()->json([
            'data' => $pollForVoting->execute($poll),
        ], 201);
    }

    public function show(Poll $poll, GetPollForVotingQuery $pollForVoting): JsonResponse
    {
        return response()->json([
            'data' => $pollForVoting->execute($poll),
        ]);
    }
}
