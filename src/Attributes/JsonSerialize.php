<?php

namespace Mobijay\JsonSerializer\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT|Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
class JsonSerialize
{
    private string $key;

    public function __construct(?string $key = null)
    {
        $this->$key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }


}
