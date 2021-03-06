<?php

namespace ARKEcosystem\UserInterface\Rules;

use ARKEcosystem\UserInterface\Rules\Concerns\ValidatesMarkdown;
use Illuminate\Contracts\Validation\Rule;

class MarkdownMaxChars implements Rule
{
    use ValidatesMarkdown;

    private int $maxChars;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $maxChars)
    {
        $this->maxChars = $maxChars;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $text = $this->getText($value);

        return mb_strlen($text) <= $this->maxChars;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('ui::validation.custom.max_markdown_chars', ['max' => $this->maxChars]);
    }
}
