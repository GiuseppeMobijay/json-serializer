<?php

namespace Mobijay\JsonSerializer;

use Illuminate\Support\Collection;
use Mobijay\JsonSerializer\Attributes\JsonSerialize;


trait JsonSerializeByAttribute
{

    public function jsonSerialize(): mixed
    {
        $reflectionObject = new \ReflectionObject($this);

        $reflectionProperties = $reflectionObject->getProperties();

        $reflectionMethods = $reflectionObject->getMethods();
        $reflectionPropertiesAndMethods = array_merge($reflectionProperties, $reflectionMethods);

        $jsonPropertiesAssociativeArray = (new Collection($reflectionPropertiesAndMethods))
            ->mapWithKeys(function (\Reflector $reflectionItem) {
                $jsonSerializeAttribute = $reflectionItem->getAttributes(JsonSerialize::class) ? $reflectionItem->getAttributes(JsonSerialize::class)[0] : null;
                $jsonKey = $this->getJsonKey($reflectionItem, $jsonSerializeAttribute);
                $ret = ($jsonSerializeAttribute) ?
                    match ($reflectionItem::class) {
                        \ReflectionProperty::class => [$jsonKey => $reflectionItem->isInitialized($this) ? $reflectionItem->getValue($this) : null],
                        \ReflectionMethod::class => [$jsonKey => $reflectionItem->invoke($this)]
                    }
                    :
                    [];
                if ($ret[$jsonKey] || $this->showNull($jsonSerializeAttribute))
                    return $ret;
            });


        return $jsonPropertiesAssociativeArray->toArray();
    }


    private function getJsonKey(\Reflector $reflectionItem, $jsonSerializeAttribute)
    {
        return isset($jsonSerializeAttribute) ? (count($jsonSerializeAttribute->getArguments()) > 0 ? ($jsonSerializeAttribute->getArguments())[0] : $reflectionItem->getName()) : null;
    }

    private function showNull($jsonSerializeAttribute): bool
    {
        $ret = true;
        if ($jsonSerializeAttribute && count($jsonSerializeAttribute->getArguments()) > 0){
            $ret = $jsonSerializeAttribute->getArguments()[1] !== false;
        }
        return $ret;
    }
}
