<?php

namespace App\Http\Controllers;

use App\Http\Requests\XmlUploadRequest;
use App\Services\XmlService;
use Illuminate\Http\Request;

class XmlUploadController extends Controller
{
    public function store(XmlUploadRequest $request)
    {
        $personsFile = $request->file('persons');
        $shipOrdersFile = $request->file('shiporders');

        $xmlService = new XmlService;

        try {
            $people = $xmlService->parsePeopleXml($personsFile->get());
            $orders = $xmlService->parseShipOrdersXml($shipOrdersFile->get());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', __('xml-upload.success'));
    }
}
