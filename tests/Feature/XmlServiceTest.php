<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\ShipOrder;
use App\Services\XmlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use RuntimeException;
use Tests\TestCase;

class XmlServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->valid_people_xml = file_get_contents(__DIR__ . '/../stubs/people.xml');
        $this->invalid_people_xml = file_get_contents(__DIR__ . '/../stubs/people_invalid.xml');

        $this->valid_shiporders_xml = file_get_contents(__DIR__ . '/../stubs/shiporders.xml');
        $this->invalid_shiporders_xml = file_get_contents(__DIR__ . '/../stubs/shiporders_invalid.xml');
    }

    public function test_it_parses_a_xml_string()
    {
        $xml = $this->valid_people_xml;

        $parsed = (new XmlService)->parse($xml);
        $this->assertIsObject($parsed);
    }

    public function test_it_throws_an_exception_when_xml_is_invalid()
    {
        $xml = $this->invalid_people_xml;

        $this->expectException(RuntimeException::class);
        $parsed = (new XmlService)->parse($xml);
    }

    public function test_it_returns_a_collection_of_people_when_processing_people_xml()
    {
        $xml = $this->valid_people_xml;

        $people = (new XmlService)->parsePeopleXml($xml);

        $this->assertInstanceOf(Collection::class, $people);
        $this->assertContainsOnlyInstancesOf(Person::class, $people);
    }

    public function test_it_stores_people_xml_data_into_database()
    {
        $xml = $this->valid_people_xml;

        $this->assertDatabaseCount('people', 0);

        $people = (new XmlService)->parsePeopleXml($xml);

        $this->assertDatabaseCount('people', 3);
        $this->assertDatabaseCount('phones', 5);
    }

    public function test_it_will_not_duplicate_person_if_already_exists_in_database()
    {
        $xml = $this->valid_people_xml;

        $this->assertDatabaseCount('people', 0);

        $people = (new XmlService)->parsePeopleXml($xml);

        $this->assertDatabaseCount('people', 3);

        $people = (new XmlService)->parsePeopleXml($xml);

        $this->assertDatabaseCount('people', 3);
    }

    public function test_it_returns_a_collection_of_shiporders_when_processing_shiporder_xml()
    {
        $xml = $this->valid_shiporders_xml;

        Person::factory(3)->create();

        $shipOrders = (new XmlService)->parseShipOrdersXml($xml);

        $this->assertInstanceOf(Collection::class, $shipOrders);
        $this->assertContainsOnlyInstancesOf(ShipOrder::class, $shipOrders);
    }

    public function test_it_stores_shiporders_xml_data_into_database()
    {
        $shipOrdersXml = $this->valid_shiporders_xml;

        Person::factory(3)->create();

        $shipOrders = (new XmlService)->parseShipOrdersXml($shipOrdersXml);

        $this->assertDatabaseCount('ship_orders', 3);

    }
}
