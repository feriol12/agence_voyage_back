<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs (admin uniquement)
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtrer par rôle (client uniquement si demandé)
        if ($request->role === 'client') {
            $query->where('is_admin', false);
        }

        // Filtrer par rôle (admin uniquement si demandé)
        if ($request->role === 'admin') {
            $query->where('is_admin', true);
        }

        $users = $query->orderBy('name')->get();

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    /**
     * Récupérer un utilisateur spécifique
     */
    public function show(User $user)
    {
        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    /**
     * Créer un utilisateur (admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin ?? false
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Utilisateur créé avec succès',
            'data' => $user
        ], 201);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'is_admin' => 'sometimes|boolean'
        ]);

        $user->update($request->only(['name', 'email', 'is_admin']));

        return response()->json([
            'status' => true,
            'message' => 'Utilisateur mis à jour',
            'data' => $user
        ]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }
}
