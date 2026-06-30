<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with(['pricing'])
        ->select('id', 'name', 'description', 'requires_quote', 'thumbnail_url', 'is_active', 'configurable_options')
        ->where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->get();

        if ($services->isEmpty()) {
            return response()->json([
                'message' => 'no_found',
            ], 404);
        }
        return $this->returnSuccess(200, $services);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
