<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Queries\Polls\GetPollResultsQuery;
use Illuminate\Http\JsonResponse;

class PollResultsController extends Controller
{
    public function __invoke(Poll $poll, GetPollResultsQuery $pollResults): JsonResponse
    {
        return response()->json([
            'data' => $pollResults->execute($poll),
        ]);
    }
}
