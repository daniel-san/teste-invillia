<?php

namespace App\Http\Controllers;

use App\Http\Requests\XmlUploadRequest;
use App\Jobs\ProcessXmlFilesJob;
use App\Services\XmlService;

class XmlUploadController extends Controller
{
    public function store(XmlUploadRequest $request, XmlService $xmlService)
    {
        $peopleFile = $request->file('people');
        $shipOrdersFile = $request->file('shiporders');

        if ($request->get('async')) {
            dispatch(new ProcessXmlFilesJob($peopleFile->get(), $shipOrdersFile->get()));
            return back()->with('success', __('xml-upload.queued'));
        }

        try {
            $people = $xmlService->parsePeopleXml($peopleFile->get());
            $orders = $xmlService->parseShipOrdersXml($shipOrdersFile->get());
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return back()->with('success', __('xml-upload.success'));
    }
}
