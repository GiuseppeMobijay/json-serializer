<?php


use Carbon\Carbon;
use Mobijay\JsonSerializer\Attributes\JsonSerialize;
use Mobijay\JsonSerializer\JsonSerializeByAttribute;
use PHPUnit\Framework\TestCase;

;

class JsonSerializeByAnnotationTest extends TestCase
{
//    use RefreshDatabase;

    protected function setUp(): void
    {
        //PARENT SETUP STA' SEMPRE ALL'INIZIO
        parent::setUp();
        //AGGIUNGI CODICE QUI
    }

    protected function tearDown(): void
    {
//        echo "teardown\n";
        parent::tearDown();
    }


    public function testOne()
    {
        $json = json_encode(new JsonTestOne());
        //occhio alla formattazione, deve essere senza spazi
        $json_expected = '{"nullField2":null,"customPrivateField":"privateFieldValue",
"publicField":"publicFieldValue",
"carbonField":"2023-10-23T11:33:32.000000Z",
"unSetArrayField":null,
"arrayField":["test1","test2"],
"associativeArrayField":{"testKey1":"testValue1","testKey2":"testValue2"},
"jsonTestOneNested2":{"nestedCustomPrivateField":"nestedPrivateFieldValue"},
"objectWithoutSerializable":{"publicFieldWithoutSerializable":"publicFieldWithoutSerializableValue"},
"customPublicMethod":"publicMethodValue",
"privateMethod":"privateMethodValue"}';
        $json_expected = str_replace(["\n", "\t"], ["", ""], $json_expected);
        $this->assertEquals($json_expected, $json);
    }


}


class JsonTestOne implements \JsonSerializable
{
    use JsonSerializeByAttribute;

    #[JsonSerialize('nullField1',false)]
    private $nullField1;
    #[JsonSerialize('nullField2',true)]
    private $nullField2 = null;
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
}


