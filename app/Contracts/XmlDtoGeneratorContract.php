<?php

namespace App\Contracts;

interface XmlDtoGeneratorContract
{
    /**
     * Generates a DTO with the data from a SimpleXMLElement object.
     *
     * @param SimpleXMLElement $shipOrderXml
     * @return array
     */
    public function getAttributesFromXml($xml);

    /**
     * Generates a DTO with the data from a SimpleXMLElement object.
     *
     * @param SimpleXMLElement $shipOrderXml
     * @return array
     */
    public static function attributesFromXml($xml);
}
