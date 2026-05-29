<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::orderBy('created_at', 'desc')->get();
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:article,video,contact',
            'url' => 'nullable|url|max:255',
        ]);

        $resource = Resource::create($validated);

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'create_resource',
            'details' => "Nouvelle ressource créée : \"{$resource->titre}\" ({$resource->type}).",
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('admin.resources.index')->with('success', 'Ressource créée avec succès.');
    }

    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        return view('admin.resources.edit', compact('resource'));
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:article,video,contact',
            'url' => 'nullable|url|max:255',
        ]);

        $resource->update($validated);

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'update_resource',
            'details' => "Ressource mise à jour : \"{$resource->titre}\" ({$resource->type}).",
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('admin.resources.index')->with('success', 'Ressource mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'delete_resource',
            'details' => "Ressource supprimée : \"{$resource->titre}\" ({$resource->type}).",
            'ip_address' => request()->ip()
        ]);

        $resource->delete();

        return redirect()->route('admin.resources.index')->with('success', 'Ressource supprimée avec succès.');
    }
}
