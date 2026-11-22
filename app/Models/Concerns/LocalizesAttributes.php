<?php

namespace App\Models\Concerns;

use App\Support\LocaleText;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait LocalizesAttributes
{
    protected function localizedAttribute(string $attribute): Attribute
    {
        return Attribute::make(
            get: function (?string $value) use ($attribute) {
                $bangla = $this->attributes[$attribute.'_bn'] ?? null;

                return LocaleText::pick($value, is_string($bangla) ? $bangla : null);
            },
        );
    }
}
