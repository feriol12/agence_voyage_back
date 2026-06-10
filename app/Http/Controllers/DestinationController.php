<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Http\Requests\DestinationRequest;
use App\Http\Resources\DestinationResource;
use App\Http\Resources\DestinationCollection;

class DestinationController extends Controller
{
       public function index()
    {
         $destinations = Destination::paginate(15);
    return response()->json($destinations);
    }

    public function store(DestinationRequest $request)
    {
        $destination = Destination::create($request->validated());
        return new DestinationResource($destination);
    }

    public function show($id)
    {
        $destination = Destination::findOrFail($id);
        return new DestinationResource($destination);
    }

    public function update(DestinationRequest $request, $id)
    {
        $destination = Destination::findOrFail($id);
        $destination->update($request->validated());
        return new DestinationResource($destination);
    }

    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);
        $destination->delete();
        return response()->json(['message' => 'Destination supprimée avec succès'], 200);
    }

}
