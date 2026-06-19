<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    // 📌 LISTE DES TRIPS (avec pagination)
  

    public function index(Request $request)
    {
        $query = Trip::with(['destination', 'tripDates']);

        // 🔥 RECHERCHE par titre ou référence
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('reference', 'LIKE', '%' . $search . '%');
            });
        }

        // 🔥 FILTRE par statut (disponible, complet, ferme)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔥 FILTRE par actif/inactif (is_active)
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // 🔥 TRI par date de création (le plus récent d'abord)
        // $trips = $query->orderBy('created_at', 'desc')->paginate(3);
        // 🔥 Nombre d'éléments par page (par défaut 10)
        $perPage = $request->per_page ?? 10;

        $trips = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($trips);
    }

    // 📌 CREATE TRIP
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'title' => 'required|string|max:255',
            'reference' => 'required|string|unique:trips,reference',
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:disponible,complet,ferme',
            'is_active' => 'boolean'
        ]);

        $trip = Trip::create($validated);

        return response()->json([
            'message' => 'Trip created successfully',
            'data' => $trip
        ], 201);
    }

    // 📌 SHOW TRIP
    public function show($id)
    {
        $trip = Trip::with('destination', 'tripDates')->find($id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }

        return response()->json([
            'data' => $trip
        ]);
    }

    // 📌 UPDATE TRIP
    public function update(Request $request, $id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }

        $validated = $request->validate([
            'destination_id' => 'sometimes|exists:destinations,id',
            'title' => 'sometimes|string|max:255',
            'reference' => 'sometimes|string|unique:trips,reference,' . $id,
            'description' => 'nullable|string',
            'duration_days' => 'sometimes|integer|min:1',
            'capacity' => 'sometimes|integer|min:1',
            'base_price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:disponible,complet,ferme',
            'is_active' => 'boolean'
        ]);

        $trip->update($validated);

        return response()->json([
            'message' => 'Trip updated successfully',
            'data' => $trip
        ]);
    }

    // 📌 DELETE TRIP
    public function destroy($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }

        $trip->delete();

        return response()->json([
            'message' => 'Trip deleted successfully'
        ]);
    }
}
