<?php

namespace App\Http\Controllers;

use App\Models\LinkSubmission;
use App\Models\User;
use Illuminate\Http\Request;

class LinkSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = LinkSubmission::query();

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if (! $user->hasAnyRole(['admin', 'encargado'])) {
            $query->where('user_id', $user->id);
        }

        $submissions = $query->with('user', 'assignedBy')->latest()->paginate(15);

        return view('submissions.index', compact('submissions'));
    }

    public function create()
    {
        $user = auth()->user();
        if (! $user->hasAnyRole(['admin', 'encargado'])) {
            abort(403);
        }

        $roles = \Spatie\Permission\Models\Role::pluck('name');

        return view('submissions.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (! $user->hasAnyRole(['admin', 'encargado'])) {
            abort(403);
        }

        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:2000',
            'action' => 'required|string|in:like-comentario,denuncia',
            'due_date' => 'nullable|date',
        ]);

        $users = User::role($validated['role'])->get();
        $count = 0;
        
        foreach ($users as $targetUser) {
            LinkSubmission::create([
                'user_id' => $targetUser->id,
                'platform' => $validated['platform'],
                'url' => $validated['url'],
                'block' => $targetUser->bloque,
                'action' => $validated['action'],
                'due_date' => $validated['due_date'],
                'assigned_by' => $user->id,
            ]);
            $count++;
        }

        return redirect()->route('submissions.index')
            ->with('success', "Tarea asignada a $count usuario(s) con el rol {$validated['role']}.");
    }

    public function show(Request $request, LinkSubmission $submission)
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['admin', 'encargado']) && $submission->user_id !== $user->id) {
            abort(403);
        }

        if ($request->ajax()) {
            return view('submissions._show_modal', compact('submission'))->render();
        }

        return view('submissions.show', compact('submission'));
    }

    public function submit(Request $request, LinkSubmission $submission)
    {
        $user = auth()->user();

        if ($submission->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:4096',
            'user_comment' => 'nullable|string|max:1000',
        ]);

        $paths = [];
        foreach ($request->file('images') as $image) {
            $paths[] = $image->store('submissions', 'public');
        }

        $submission->update([
            'image_path' => $paths,
            'user_comment' => $data['user_comment'] ?? null,
            'status' => 'subido',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Imagen subida correctamente.');
    }

    public function approve(Request $request, LinkSubmission $submission)
    {
        $user = auth()->user();
        if (! $user->hasAnyRole(['admin', 'encargado'])) {
            abort(403);
        }

        $data = $request->validate([
            'admin_comment' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'status' => 'aprobado',
            'admin_comment' => $data['admin_comment'] ?? null,
        ]);

        return back()->with('success', 'Tarea aprobada correctamente.');
    }

    public function reject(Request $request, LinkSubmission $submission)
    {
        $user = auth()->user();
        if (! $user->hasAnyRole(['admin', 'encargado'])) {
            abort(403);
        }

        $data = $request->validate([
            'admin_comment_reject' => 'required|string|max:1000',
        ]);

        $submission->update([
            'status' => 'rechazado',
            'admin_comment' => $data['admin_comment_reject'],
        ]);

        return back()->with('success', 'Tarea rechazada correctamente.');
    }

    public function update(Request $request, LinkSubmission $submission)
    {
        $user = auth()->user();
        if (! $user->hasAnyRole(['admin', 'encargado'])) {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|in:pendiente,subido,aprobado,rechazado',
            'admin_comment' => 'nullable|string|max:1000',
        ]);

        $submission->update($data);

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
