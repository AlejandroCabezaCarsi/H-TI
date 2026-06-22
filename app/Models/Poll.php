<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([self::QUESTION])]
class Poll extends Model
{
    use HasFactory, HasUlids;

    public const TABLE = 'polls';

    public const ID = 'id';

    public const QUESTION = 'question';

    protected $table = self::TABLE;

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class, PollOption::POLL_ID, self::ID);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, Vote::POLL_ID, self::ID);
    }
}
