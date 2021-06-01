<?php

namespace App\Services;

use App\Contracts\XmlDtoGeneratorContract;
use App\Models\Person;
use App\Models\ShipOrder;
use App\Repositories\PersonRepository;
use App\Repositories\Repository;
use App\Repositories\ShipOrderRepository;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class XmlService
{
    /**
     * Person repository instance.
     *
     * @var Repository
     */
    protected $personRepository;

    /**
     * ShipOrder repository instance.
     *
     * @var Repository
     */
    protected $shipOrderRepository;

    public function __construct(PersonRepository $personRepository, ShipOrderRepository $shipOrderRepository)
    {
        $this->personRepository = $personRepository;
        $this->shipOrderRepository = $shipOrderRepository;
    }

    /**
     * Alias for the simplexml_load_string function.
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
     * Process a xml string and save its contents to the database.
     *
     * @param string $xml
     * @param Repository $repository
     * @param XmlDtoGeneratorContract $dtoGenerator
     * @return Collection|Model[]
     */
    public function processXml($xml, Repository $repository, XmlDtoGeneratorContract $dtoGenerator)
    {
        $parsedData = $this->parse($xml);

        $entities = collect();

        try {
            DB::beginTransaction();
            foreach ($parsedData as $entityData) {
                $attributes = $dtoGenerator->getAttributesFromXml($entityData);

                if ($entity = $repository->find($attributes['id'])) {
                    $entities->push($repository->update($entity, $attributes));
                    continue;
                }

                $entities->push($repository->create($attributes));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $entities;
    }

    /**
     * Processes a xml file containing Person data and saves them into the database.
     *
     * @return Collection|Person[]
     */
    public function parsePeopleXml($xml)
    {
        return $this->processXml($xml, $this->personRepository, new Person());
    }


    /**
     * Processes a xml file containing ShipOrder data and saves them into the database.
     *
     * @return Collection|ShipOrder[]
     */
    public function parseShipOrdersXml($xml)
    {
        return $this->processXml($xml, $this->shipOrderRepository, new ShipOrder());
    }
}
