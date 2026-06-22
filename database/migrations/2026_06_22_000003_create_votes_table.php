<?php

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Vote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Vote::TABLE, function (Blueprint $table): void {
            $table->ulid(Vote::ID)->primary();
            $table->foreignUlid(Vote::POLL_ID)
                ->constrained(Poll::TABLE, Poll::ID)
                ->cascadeOnDelete();
            $table->foreignUlid(Vote::POLL_OPTION_ID)
                ->constrained(PollOption::TABLE, PollOption::ID)
                ->cascadeOnDelete();
            $table->timestamps();

            $table->index([Vote::POLL_ID, Vote::POLL_OPTION_ID]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Vote::TABLE);
    }
};
