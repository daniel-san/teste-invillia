<?php

namespace App\Jobs;

use App\Services\XmlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessXmlFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string */
    protected $peopleXml;

    /** @var string */
    protected $shipOrdersXml;

    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($peopleXml, $shipOrdersXml)
    {
        $this->peopleXml = $peopleXml;
        $this->shipOrdersXml = $shipOrdersXml;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(XmlService $service)
    {
        $people = $service->parsePeopleXml($this->peopleXml);
        $orders = $service->parseShipOrdersXml($this->shipOrdersXml);
    }
}
