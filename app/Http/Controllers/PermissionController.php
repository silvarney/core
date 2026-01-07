<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return Inertia::render('Permissions/Index', [
            'permissions' => Permission::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()->back()->with('message', 'Permissão criada com sucesso!');
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('message', 'Permissão atualizada com sucesso!');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->back()->with('message', 'Permissão excluída com sucesso!');
    }
}
