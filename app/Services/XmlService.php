<?php

namespace App\Services;

use RuntimeException;

class XmlService
{

    /**
     * Alias for the simplexml_load_string function
     *
     * @throws RuntimeException
     * @return SimpleXML
     */
    public function parse($xml)
    {
        try {
            $parsedXml = simplexml_load_string($xml);
        } catch (\Exception $e) {
            throw new RuntimeException("Xml file is invalid");
        }

        return $parsedXml;
    }

    /**
     * Parses a xml file containing Person data
     *
     * @return Person[]
     */
    public function parsePeopleXml($xml)
    {
        $parsed = $this->parse($xml);


    }
}
