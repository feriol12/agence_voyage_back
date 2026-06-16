<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripDate;
use Illuminate\Http\Request;

class TripDateController extends Controller
{
    // Récupérer toutes les dates d'un voyage
    public function index(Trip $trip)
    {
        $dates = $trip->tripDates()->orderBy('start_date')->get();

        return response()->json([
            'status' => true,
            'data' => $dates
        ]);
    }

    // Ajouter une date à un voyage
    public function store(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'places_available' => 'required|integer|min:0'
        ]);

        $tripDate = $trip->tripDates()->create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Date ajoutée avec succès',
            'data' => $tripDate
        ], 201);
    }

   // Modifier une date
    public function update(Request $request, Trip $trip, TripDate $tripDate)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'places_available' => 'required|integer|min:0'
        ]);

        $tripDate->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Date modifiée avec succès',
            'data' => $tripDate->fresh()
        ]);
    }

   // Supprimer une date
public function destroy(Trip $trip, TripDate $tripDate)
{
    $tripDate->delete();

    return response()->json([
        'status' => true,
        'message' => 'Date supprimée avec succès'
    ]);
}
}
