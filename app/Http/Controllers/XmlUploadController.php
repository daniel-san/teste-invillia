<?php

namespace App\Http\Controllers;

use App\Http\Requests\XmlUploadRequest;
use Illuminate\Http\Request;

class XmlUploadController extends Controller
{
    public function store(XmlUploadRequest $request)
    {
        $personsFile = $request->file('persons');
        $shipOrdersFile = $request->file('shiporders');


        return back()->with('success', __('xml-upload.success'));
    }
}
