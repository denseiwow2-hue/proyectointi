<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar permiso
        if (!auth()->user()->can('ver documentos')) {
            abort(403, 'No tienes permiso para ver documentos');
        }
        
        // Lógica para listar documentos
        return view('documentos.index', [
            'documentos' => [] // Aquí irían tus documentos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permiso
        if (!auth()->user()->can('crear documentos')) {
            abort(403, 'No tienes permiso para crear documentos');
        }
        
        return view('documentos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar permiso
        if (!auth()->user()->can('crear documentos')) {
            abort(403, 'No tienes permiso para crear documentos');
        }
        
        // Validar datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file'
        ]);
        
        // Crear documento
        // $documento = Document::create($validated);
        
        return redirect()->route('documentos.index')
            ->with('success', 'Documento creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->can('ver documentos')) {
            abort(403, 'No tienes permiso para ver documentos');
        }
        
        // $documento = Document::find($id);
        // return view('documentos.show', compact('documento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('editar documentos')) {
            abort(403, 'No tienes permiso para editar documentos');
        }
        
        // $documento = Document::find($id);
        // return view('documentos.edit', compact('documento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('editar documentos')) {
            abort(403, 'No tienes permiso para editar documentos');
        }
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        
        // $documento = Document::find($id);
        // $documento->update($validated);
        
        return redirect()->route('documentos.index')
            ->with('success', 'Documento actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('eliminar documentos')) {
            abort(403, 'No tienes permiso para eliminar documentos');
        }
        
        // $documento = Document::find($id);
        // $documento->delete();
        
        return redirect()->route('documentos.index')
            ->with('success', 'Documento eliminado exitosamente');
    }
}
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
