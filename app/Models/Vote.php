<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([self::POLL_ID, self::POLL_OPTION_ID])]
class Vote extends Model
{
    use HasFactory, HasUlids;

    public const TABLE = 'votes';

    public const ID = 'id';

    public const POLL_ID = 'poll_id';

    public const POLL_OPTION_ID = 'poll_option_id';

    protected $table = self::TABLE;

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, self::POLL_ID, Poll::ID);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(PollOption::class, self::POLL_OPTION_ID, PollOption::ID);
    }
}
