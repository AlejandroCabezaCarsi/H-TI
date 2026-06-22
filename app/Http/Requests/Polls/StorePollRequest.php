<?php

namespace App\Http\Requests\Polls;

use App\Models\Poll;
use Illuminate\Foundation\Http\FormRequest;

class StorePollRequest extends FormRequest
{
    public const OPTIONS = 'options';

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
            Poll::QUESTION => ['required', 'string', 'min:5', 'max:255'],
            self::OPTIONS => ['required', 'array', 'min:2', 'max:5'],
            self::OPTIONS.'.*' => ['required', 'string', 'distinct:strict', 'max:120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $question = $this->input(Poll::QUESTION);
        $options = $this->input(self::OPTIONS);

        $this->merge([
            Poll::QUESTION => is_string($question) ? trim($question) : $question,
            self::OPTIONS => is_array($options)
                ? array_map(fn (mixed $option): mixed => is_string($option) ? trim($option) : $option, $options)
                : $options,
        ]);
    }

    public function pollQuestion(): string
    {
        return (string) $this->validated(Poll::QUESTION);
    }

    /**
     * @return list<string>
     */
    public function optionTexts(): array
    {
        return array_values($this->validated(self::OPTIONS));
    }
}
