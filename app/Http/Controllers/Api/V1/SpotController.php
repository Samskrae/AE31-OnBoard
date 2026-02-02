<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Spot;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpotResource;
use App\Http\Requests\Api\V1\StoreSpotRequest;
use App\Http\Requests\Api\V1\UpdateSpotRequest;
use Illuminate\Http\Response;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource (all spots).
     */
    public function index()
    {
        $spots = Spot::all();
        return SpotResource::collection($spots);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpotRequest $request)
    {
        $validated = $request->validated();
        
        $spot = Spot::create($validated);
        
        return new SpotResource($spot);
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
        return new SpotResource($spot);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpotRequest $request, Spot $spot)
    {
        $validated = $request->validated();
        
        $spot->update($validated);
        
        return new SpotResource($spot);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        $spot->delete();
        
        return response()->noContent();
    }
}
