<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([self::POLL_ID, self::TEXT])]
class PollOption extends Model
{
    use HasFactory, HasUlids;

    public const TABLE = 'poll_options';

    public const ID = 'id';

    public const POLL_ID = 'poll_id';

    public const TEXT = 'text';

    protected $table = self::TABLE;

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, self::POLL_ID, Poll::ID);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, Vote::POLL_OPTION_ID, self::ID);
    }
}
