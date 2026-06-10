<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function getUsersStats(Request $request)
    {
        // Nombre total d'utilisateurs (exclure les admins)
        $totalUsers = User::where('is_admin', false)->count();

        // Début du mois en cours
        $startOfMonth = now()->startOfMonth();

        // Nouveaux utilisateurs ce mois-ci (exclure admins)
        $newUsersThisMonth = User::where('is_admin', false)
            ->where('created_at', '>=', $startOfMonth)
            ->count();

        // Optionnel : nouveaux utilisateurs le mois dernier (pour tendance)
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        $newUsersLastMonth = User::where('is_admin', false)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // Calculer la tendance
        $trend = 0;
        if ($newUsersLastMonth > 0) {
            $trend = round(($newUsersThisMonth - $newUsersLastMonth) / $newUsersLastMonth * 100, 1);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'total' => $totalUsers,
                'new_this_month' => $newUsersThisMonth,
                'new_last_month' => $newUsersLastMonth,
                'trend' => $trend
            ]
        ]);
    }
}
