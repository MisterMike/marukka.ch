<?php
namespace modules\sitemodule\fields;

use craft\base\ElementInterface;
use craft\fields\PlainText;

/** A normal multiline text input that keeps code indentation and blank lines intact. */
class WhitespaceText extends PlainText
{
    public static function displayName(): string
    {
        return 'Plain Text (preserve whitespace)';
    }

    public function normalizeValue(mixed $value, ?ElementInterface $element): mixed
    {
        return $value === null ? null : (string)$value;
    }

    public function normalizeValueFromRequest(mixed $value, ?ElementInterface $element): mixed
    {
        return $this->normalizeValue($value, $element);
    }

    public function serializeValue(mixed $value, ?ElementInterface $element): mixed
    {
        return $this->normalizeValue($value, $element);
    }
}
