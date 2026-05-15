<?php

namespace App\Http\Controllers;

use App\Models\LinkSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['admin', 'encargado'])) {
            $baseQuery = LinkSubmission::query();
        } else {
            $baseQuery = LinkSubmission::where('user_id', $user->id);
        }

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'pendiente')->count();
        $awaiting = (clone $baseQuery)->where('status', 'subido')->count();
        $approved = (clone $baseQuery)->where('status', 'aprobado')->count();
        $rejected = (clone $baseQuery)->where('status', 'rechazado')->count();

        $statusCounts = (clone $baseQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $platformCounts = (clone $baseQuery)
            ->select('platform', DB::raw('count(*) as total'))
            ->groupBy('platform')
            ->pluck('total', 'platform')
            ->toArray();

        $isAdmin = $user->hasAnyRole(['admin', 'encargado']);

        return view('home', compact('total', 'pending', 'awaiting', 'approved', 'rejected', 'statusCounts', 'platformCounts', 'isAdmin'));
    }
}
