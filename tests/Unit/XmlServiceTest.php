<?php

namespace Tests\Unit;

use App\Services\XmlService;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class XmlServiceTest extends TestCase
{

    public function test_it_parses_a_xml_string()
    {
        $xml = <<<XML
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

        $parsed = (new XmlService)->parse($xml);
        $this->assertIsObject($parsed);
    }

    public function test_it_throws_an_exception_when_xml_is_invalid()
    {
        $xml = <<<XML
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
        $this->expectException(RuntimeException::class);
        $parsed = (new XmlService)->parse($xml);
    }

    public function test_it_returns_a_collection_of_people_when_parsing_people_xml()
    {
    }
}
