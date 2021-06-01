<?php

namespace App\Services;

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
     * Processes a xml file containing Person data and saves them into the database.
     *
     * @return Collection|Person[]
     */
    public function parsePeopleXml($xml)
    {
        $parsedData = $this->parse($xml);

        $people = collect();

        try {
            DB::beginTransaction();
            foreach($parsedData as $personData) {
                $attributes = Person::attributesFromXml($personData);

                if ($person = $this->personRepository->find($attributes['id'])) {
                    $people->push(
                        $this->personRepository->update($person, $attributes)
                    );
                    continue;
                }

                $people->push(
                    $this->personRepository->create($attributes)
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $people;
    }


    /**
     * Processes a xml file containing ShipOrder data and saves them into the database.
     *
     * @return Collection|ShipOrder[]
     */
    public function parseShipOrdersXml($xml)
    {
        $parsedData = $this->parse($xml);

        $shipOrders = collect();

        try {
            DB::beginTransaction();
            foreach($parsedData as $shipOrderData) {
                $attributes = ShipOrder::attributesFromXml($shipOrderData);

                if ($shipOrder = $this->shipOrderRepository->find($attributes['id'])) {
                    $shipOrders->push(
                        $this->shipOrderRepository->update($shipOrder, $attributes)
                    );
                    continue;
                }

                $shipOrders->push(
                    $this->shipOrderRepository->create($attributes)
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $shipOrders;
    }
}
