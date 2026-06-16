<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Http\Requests\DestinationRequest;
use App\Http\Resources\DestinationResource;
use App\Http\Resources\DestinationCollection;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('continent')) {
            $query->where('continent', $request->continent);
        }

        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $perPage = $request->input('per_page', 9);
        $destinations = $query->paginate($perPage);

        return new DestinationCollection($destinations);
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
        $destination->update($request->only(array_keys($request->all())));
        return new DestinationResource($destination);
    }


    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);
        $destination->delete();
        return response()->json(['message' => 'Destination supprimée avec succès'], 200);
    }
}
