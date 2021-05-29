<?php

namespace Tests\Unit;

use App\Models\Person;
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
}
