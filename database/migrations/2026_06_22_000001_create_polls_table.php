<?php

use App\Models\Poll;
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
        Schema::create(Poll::TABLE, function (Blueprint $table): void {
            $table->ulid(Poll::ID)->primary();
            $table->string(Poll::QUESTION, 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Poll::TABLE);
    }
};
