<?php

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class XmlService
{

    /**
     * Alias for the simplexml_load_string function
     *
     * @throws RuntimeException
     * @return SimpleXMLElement
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
     * Processes a xml file containing Person data into the database
     *
     * @return Collection|Person[]
     */
    public function parsePeopleXml($xml)
    {
        $parsed = $this->parse($xml);

        $people = collect();

        try {
            DB::beginTransaction();
            foreach($parsed as $personData) {

                if ($person = Person::whereId($personData->personid)->first()) {
                    $people->push($person);
                    continue;
                }

                $person = new Person();
                $person->id = $personData->personid;
                $person->name = $personData->personname;

                $people->push(
                    tap($person)->save()
                );

                foreach ($personData->phones->phone as $phone) {
                    $person->phones()->create([
                        'number' => $phone
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return $people;
    }
}
