<?php

use App\Models\Poll;
use App\Models\PollOption;
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
        Schema::create(PollOption::TABLE, function (Blueprint $table): void {
            $table->ulid(PollOption::ID)->primary();
            $table->foreignUlid(PollOption::POLL_ID)
                ->constrained(Poll::TABLE, Poll::ID)
                ->cascadeOnDelete();
            $table->string(PollOption::TEXT, 120);
            $table->timestamps();

            $table->unique([PollOption::POLL_ID, PollOption::TEXT]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(PollOption::TABLE);
    }
};
