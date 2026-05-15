<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LinkSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        // Estadísticas por bloque
        $blocks = User::select('bloque')->whereNotNull('bloque')->distinct()->pluck('bloque');

        $blockStats = $blocks->map(function($bloque) {
            $usersInBlock = User::where('bloque', $bloque)->pluck('id');
            $submissions = LinkSubmission::whereIn('user_id', $usersInBlock)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $stats = [
                'bloque' => $bloque,
                'total_usuarios' => $usersInBlock->count(),
                'total_tareas' => array_sum($submissions),
                'pendiente' => $submissions['pendiente'] ?? 0,
                'subido' => $submissions['subido'] ?? 0,
                'aprobado' => $submissions['aprobado'] ?? 0,
                'rechazado' => $submissions['rechazado'] ?? 0,
            ];

            $stats['porcentaje_aprobado'] = $stats['total_tareas'] > 0 ? round(($stats['aprobado'] / $stats['total_tareas']) * 100, 1) : 0;
            $stats['porcentaje_pendiente'] = $stats['total_tareas'] > 0 ? round(($stats['pendiente'] / $stats['total_tareas']) * 100, 1) : 0;

            return $stats;
        });

        // Si se selecciona un bloque específico
        $selectedBlock = $request->get('bloque');
        $blockUsers = collect();
        $userStats = collect();

        if ($selectedBlock) {
            $usersInBlock = User::where('bloque', $selectedBlock)->get();

            if ($usersInBlock->isNotEmpty()) {
                $blockUsers = $usersInBlock->map(function($user) {
                    $submissions = LinkSubmission::where('user_id', $user->id)
                        ->select('status', DB::raw('count(*) as total'))
                        ->groupBy('status')
                        ->pluck('total', 'status')
                        ->toArray();

                    $stats = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'ci' => $user->ci,
                        'total_tareas' => array_sum($submissions),
                        'pendiente' => $submissions['pendiente'] ?? 0,
                        'subido' => $submissions['subido'] ?? 0,
                        'aprobado' => $submissions['aprobado'] ?? 0,
                        'rechazado' => $submissions['rechazado'] ?? 0,
                    ];

                    $stats['porcentaje_aprobado'] = $stats['total_tareas'] > 0 ? round(($stats['aprobado'] / $stats['total_tareas']) * 100, 1) : 0;

                    return $stats;
                })->filter(function($user) {
                    return $user['total_tareas'] > 0;
                });

                // Estadísticas del bloque para gráficos
                $userStats = $blockUsers->pluck('porcentaje_aprobado', 'name');
            }
        }

        // Estadísticas globales para gráficos
        $blockPercentages = $blockStats->pluck('porcentaje_aprobado', 'bloque');

        return view('admin.reports.index', compact(
            'blockStats',
            'selectedBlock',
            'blockUsers',
            'userStats',
            'blockPercentages'
        ));
    }
}
