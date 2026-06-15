<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClientTrip;
use App\Models\User;
use App\Models\Trip;
use App\Models\TripDate;
use Illuminate\Http\Request;

class ClientTripController extends Controller
{
    // Liste de toutes les inscriptions (admin)
    public function index(Request $request)
    {
        $query = ClientTrip::with(['client', 'trip', 'tripDate', 'assignedBy']);

        // Filtre par statut
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par client
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtre par voyage
        if ($request->has('trip_id')) {
            $query->where('trip_id', $request->trip_id);
        }

        $clientTrips = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'status' => true,
            'data' => $clientTrips
        ]);
    }

    // Récupérer les inscriptions d'un client spécifique
    public function getClientTrips(User $user)
    {
        $clientTrips = $user->clientTrips()
            ->with(['trip', 'tripDate', 'assignedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $clientTrips
        ]);
    }

    // Créer une inscription
  public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'trip_id' => 'required|exists:trips,id',
        'trip_date_id' => 'required|exists:trip_dates,id',
        'notes' => 'nullable|string',
    ]);

    // ✅ Vérifier que l'utilisateur n'est PAS un admin
    $user = User::find($validated['user_id']);
    if ($user->is_admin) {
        return response()->json([
            'status' => false,
            'message' => 'Impossible d\'inscrire un administrateur à un voyage'
        ], 400);
    }

    // Vérifier que la date appartient bien au voyage
    $tripDate = TripDate::where('id', $validated['trip_date_id'])
        ->where('trip_id', $validated['trip_id'])
        ->first();

    if (!$tripDate) {
        return response()->json([
            'status' => false,
            'message' => 'La date sélectionnée n\'appartient pas à ce voyage'
        ], 400);
    }

    $validated['assigned_by'] = $request->user()->id;
    $validated['assigned_at'] = now();
    $validated['status'] = 'pending';

    $clientTrip = ClientTrip::create($validated);

    return response()->json([
        'status' => true,
        'message' => 'Client inscrit avec succès',
        'data' => $clientTrip->load(['client', 'trip', 'tripDate', 'assignedBy'])
    ], 201);
}

    // Mettre à jour le statut d'une inscription
    public function updateStatus(Request $request, ClientTrip $clientTrip)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $clientTrip->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Statut mis à jour avec succès',
            'data' => $clientTrip
        ]);
    }

    // Mettre à jour les notes
    public function updateNotes(Request $request, ClientTrip $clientTrip)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string'
        ]);

        $clientTrip->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Notes mises à jour avec succès',
            'data' => $clientTrip
        ]);
    }
     //pour que le client puisse voir ses voyages

    public function getMyTrips(Request $request)
{
    $user = $request->user();

    // Sécurité : si c'est un admin, il n'a pas de "mes voyages"
    if ($user->is_admin) {
        return response()->json([
            'status' => false,
            'message' => 'Les administrateurs n\'ont pas de voyages personnels'
        ], 403);
    }

    $clientTrips = $user->clientTrips()
        ->with(['trip', 'tripDate', 'assignedBy'])
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'status' => true,
        'data' => $clientTrips
    ]);
}

    // Supprimer une inscription
    public function destroy(ClientTrip $clientTrip)
    {
        $clientTrip->delete();

        return response()->json([
            'status' => true,
            'message' => 'Inscription supprimée avec succès'
        ]);
    }
}
