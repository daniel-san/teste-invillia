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
        $this->valid_people_xml = <<<XML
            <?xml version="1.0" encoding="utf-8"?>
            <people>
                <person>
                <personid>1</personid>
                <personname>Name 1</personname>
                <phones>
                    <phone>2345678</phone>
                    <phone>1234567</phone>
                </phones>
                </person>
                <person>
                <personid>2</personid>
                <personname>Name 2</personname>
                <phones>
                    <phone>4444444</phone>
                </phones>
                </person>
                <person>
                <personid>3</personid>
                <personname>Name 3</personname>
                <phones>
                    <phone>7777777</phone>
                    <phone>8888888</phone>
                </phones>
                </person>
            </people>
            XML;

        $this->invalid_people_xml =  <<<XML
            <?xml version="1.0" encoding="utf-8"?>
            <people>
                <person>
                <personid>1</personid>
                <personname>Name 1</personname>
                <phones>
                    <phone>2345678</phone>
                    <phone>1234567</phone>
                </phones>
                </person>
                <person>
                <personid>2</personid>
                <personname>Name 2</personname>
                <phones>
                    <phone>4444444</phone>
                </person>
                <person>
                <personid>3</personid>
                <personname>Name 3</personname>
                <phones>
                    <phone>7777777</phone>
                    <phone>8888888</phone>
                </phones>
                </person>
            XML;


        $this->valid_shiporders_xml = <<<XML
            <?xml version="1.0" encoding="utf-8"?>
            <shiporders>
                <shiporder>
                  <orderid>1</orderid>
                  <orderperson>1</orderperson>
                  <shipto>
                    <name>Name 1</name>
                    <address>Address 1</address>
                    <city>City 1</city>
                    <country>Country 1</country>
                  </shipto>
                  <items>
                      <item>
                        <title>Title 1</title>
                        <note>Note 1</note>
                        <quantity>745</quantity>
                        <price>123.45</price>
                      </item>
                  </items>
                </shiporder>
                <shiporder>
                  <orderid>2</orderid>
                  <orderperson>2</orderperson>
                  <shipto>
                    <name>Name 2</name>
                    <address>Address 2</address>
                    <city>City 2</city>
                    <country>Country 2</country>
                  </shipto>
                  <items>
                      <item>
                        <title>Title 2</title>
                        <note>Note 2</note>
                        <quantity>45</quantity>
                        <price>13.45</price>
                      </item>
                  </items>
                </shiporder>
                <shiporder>
                  <orderid>3</orderid>
                  <orderperson>3</orderperson>
                  <shipto>
                    <name>Name 3</name>
                    <address>Address 3</address>
                    <city>City 3</city>
                    <country>Country 3</country>
                  </shipto>
                  <items>
                      <item>
                        <title>Title 3</title>
                        <note>Note 3</note>
                        <quantity>5</quantity>
                        <price>1.12</price>
                      </item>
                      <item>
                        <title>Title 4</title>
                        <note>Note 4</note>
                        <quantity>2</quantity>
                        <price>77.12</price>
                      </item>
                  </items>
                </shiporder>
            </shiporders>
            XML;
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
