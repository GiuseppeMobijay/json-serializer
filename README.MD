The class must be `implements \JsonSerializable`

Then you have to `use JsonSerializeByAttribute` Trait. It contains the serialization method;

for each field or method you want to serialize add the following attribute
`#[JsonSerialize]
the field name is used as key in the serialized json.

If you want to change the key in the json you have to add it
#[JsonSerialize('customPrivateField')]

A complete example:

`
```class JsonTestOne implements \JsonSerializable
{
use JsonSerializeByAttribute;

    #[JsonSerialize('customPrivateField')]
    private $privateField;

    public $disturbanceField1;
    private $disturbanceField2;

    #[JsonSerialize] 
    public $publicField;

    #[JsonSerialize] public Carbon $carbonField;
    #[JsonSerialize] public array $unSetArrayField;
    #[JsonSerialize] public array $arrayField;
    #[JsonSerialize] public array $associativeArrayField;

    public JsonTestOneNested $jsonTestOneNested1;

    #[JsonSerialize]
    public JsonTestOneNested $jsonTestOneNested2;

    #[JsonSerialize("objectWithoutSerializable")]
    public JsonTestOneNestedWithoutJsonSerializable $jsonTestOneNestedWithoutJsonSerializable1;

    public function __construct()
    {
        $this->privateField = 'privateFieldValue';
        $this->publicField = 'publicFieldValue';
        $this->carbonField = Carbon::createFromFormat('Y-m-d H:i:s', '2023-10-23 11:33:32');
        $this->associativeArrayField = ["testKey1" => "testValue1", "testKey2" => "testValue2",];
        $this->arrayField = ["test1", "test2"];
        $this->jsonTestOneNested2 = new JsonTestOneNested();
        $this->jsonTestOneNestedWithoutJsonSerializable1 = new JsonTestOneNestedWithoutJsonSerializable();

    }


    #[\Mobijay\JsonSerializer\Attributes\JsonSerialize('customPublicMethod')]
    public function publicMethod()
    {
        return 'publicMethodValue';
    }

    #[\Mobijay\JsonSerializer\Attributes\JsonSerialize]
    public function privateMethod()
    {
        return 'privateMethodValue';
    }

    public function disturbanceMethod()
    {
        return 'disturbanceMethodValue';
    }

}

class JsonTestOneNested implements \JsonSerializable
{
use JsonSerializeByAttribute;

    #[JsonSerialize('nestedCustomPrivateField')]
    private $privateField = "nestedPrivateFieldValue";

}

class JsonTestOneNestedWithoutJsonSerializable
{
private $privateField = "nestedPrivateFieldValueWithout";
public $publicFieldWithoutSerializable = "publicFieldWithoutSerializableValue";
}```




