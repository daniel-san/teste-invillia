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

        $this->service = app(XmlService::class);

        $this->validPeopleXml = file_get_contents(__DIR__ . '/../stubs/people.xml');
        $this->invalidPeopleXml = file_get_contents(__DIR__ . '/../stubs/people_invalid.xml');
        $this->malformedPeopleXml = file_get_contents(__DIR__ . '/../stubs/people_malformed.xml');

        $this->validShipOrdersXml = file_get_contents(__DIR__ . '/../stubs/shiporders.xml');
        $this->invalidShipOrdersXml = file_get_contents(__DIR__ . '/../stubs/shiporders_invalid.xml');
    }

    public function test_it_parses_a_xml_string()
    {
        $xml = $this->validPeopleXml;

        $parsed = $this->service->parse($xml);
        $this->assertIsObject($parsed);
    }

    public function test_it_throws_an_exception_when_xml_is_malformed()
    {
        $xml = $this->malformedPeopleXml;

        $this->expectException(RuntimeException::class);
        $parsed = $this->service->parse($xml);
    }

    public function test_it_returns_a_collection_of_people_when_processing_people_xml()
    {
        $xml = $this->validPeopleXml;

        $people = $this->service->parsePeopleXml($xml);

        $this->assertInstanceOf(Collection::class, $people);
        $this->assertContainsOnlyInstancesOf(Person::class, $people);
    }

    public function test_it_stores_people_xml_data_into_database()
    {
        $xml = $this->validPeopleXml;

        $this->assertDatabaseCount('people', 0);

        $people = $this->service->parsePeopleXml($xml);

        $this->assertDatabaseCount('people', 3);
        $this->assertDatabaseCount('phones', 5);
    }

    public function test_it_will_not_duplicate_person_if_already_exists_in_database()
    {
        $xml = $this->validPeopleXml;

        $this->assertDatabaseCount('people', 0);

        $people = $this->service->parsePeopleXml($xml);

        $this->assertDatabaseCount('people', 3);

        $people = $this->service->parsePeopleXml($xml);

        $this->assertDatabaseCount('people', 3);
    }

    public function test_it_returns_a_collection_of_shiporders_when_processing_shiporder_xml()
    {
        $xml = $this->validShipOrdersXml;

        Person::factory(3)->create();

        $shipOrders = $this->service->parseShipOrdersXml($xml);

        $this->assertInstanceOf(Collection::class, $shipOrders);
        $this->assertContainsOnlyInstancesOf(ShipOrder::class, $shipOrders);
    }

    public function test_it_stores_shiporders_xml_data_into_database()
    {
        $shipOrdersXml = $this->validShipOrdersXml;

        Person::factory(3)->create();

        $shipOrders = $this->service->parseShipOrdersXml($shipOrdersXml);

        $this->assertDatabaseCount('ship_orders', 3);
        $this->assertDatabaseCount('ship_order_items', 4);
    }

    public function test_it_will_not_duplicate_shiporders_if_already_exists_in_database()
    {
        $xml = $this->validShipOrdersXml;

        Person::factory(3)->create();

        $this->assertDatabaseCount('ship_orders', 0);

        $orders = $this->service->parseShipOrdersXml($xml);

        $this->assertDatabaseCount('ship_orders', 3);
        $this->assertDatabaseCount('ship_order_items', 4);

        $orders = $this->service->parseShipOrdersXml($xml);

        $this->assertDatabaseCount('ship_orders', 3);
        $this->assertDatabaseCount('ship_order_items', 4);
    }
}
