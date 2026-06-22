<?php

namespace App\Http\Requests\Polls;

use App\Models\Vote;
use Illuminate\Foundation\Http\FormRequest;

class CastVoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            Vote::POLL_OPTION_ID => ['required', 'ulid'],
        ];
    }

    public function pollOptionId(): string
    {
        return (string) $this->validated(Vote::POLL_OPTION_ID);
    }
}
