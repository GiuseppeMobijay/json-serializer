<?php

namespace Mobijay\JsonSerializer\Attributes;


use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT|Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
class JsonSerialize
{
//    private string $key;
//    private bool $showNull;
//
//    public function isShowNull(): bool
//    {
//        return $this->showNull;
//    }
    function __construct(?string ...$args){
        die( "Running " . __METHOD__ . " args: " . implode(", ", $args) . PHP_EOL);
    }
//    public function __construct(?string $key = null, ?bool $showNull = true)
//    {
//        $this->$key = $key;
//        $this->showNull = $showNull;
//    }

    public function getKey(): string
    {
        return $this->key;
    }


}
