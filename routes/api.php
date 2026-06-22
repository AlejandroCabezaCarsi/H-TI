<?php

use App\Http\Controllers\Api\PollController;
use App\Http\Controllers\Api\PollResultsController;
use App\Http\Controllers\Api\PollVoteController;
use Illuminate\Support\Facades\Route;

Route::post('/polls', [PollController::class, 'store'])->name('polls.store');
Route::get('/polls/{poll}', [PollController::class, 'show'])->name('polls.show');
Route::post('/polls/{poll}/votes', PollVoteController::class)->name('polls.votes.store');
Route::get('/polls/{poll}/results', PollResultsController::class)->name('polls.results.show');
