<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShipOrderResource;
use App\Models\ShipOrder;

class ShipOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ShipOrderResource::withoutWrapping();

        return ShipOrderResource::collection(
            ShipOrder::with(['person', 'items', 'address'])->get()
        );
    }
}
