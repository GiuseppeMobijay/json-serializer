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

        $jsonPropertiesAssociativeArray = (new Collection($reflectionPropertiesAndMethods))->mapWithKeys(function (\Reflector $reflectionItem) {
            $jsonSerializeAttribute = $reflectionItem->getAttributes(JsonSerialize::class) ? $reflectionItem->getAttributes(JsonSerialize::class)[0] : null;
            return ($jsonSerializeAttribute) ?
                match ($reflectionItem::class) {
                    \ReflectionProperty::class => [$this->getJsonKey($reflectionItem,$jsonSerializeAttribute) => $reflectionItem->isInitialized($this) ? $reflectionItem->getValue($this) : null],
                    \ReflectionMethod::class => [$this->getJsonKey($reflectionItem, $jsonSerializeAttribute) => $reflectionItem->invoke($this)]
                }
                :
                [];
        });

        return $jsonPropertiesAssociativeArray->toArray();
    }



    private function getJsonKey(\Reflector $reflectionItem,  $jsonSerializeAttribute)
    {
        return count($jsonSerializeAttribute->getArguments()) > 0 ? ($jsonSerializeAttribute->getArguments())[0]: $reflectionItem->getName();
    }

}
