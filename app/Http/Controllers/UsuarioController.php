<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Module;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios
     */
    public function index()
    {
        $usuarios = User::with(['role', 'areas'])->get();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario de creación
     */

    public function create()
    {
        $areas = Area::where('active', true)->get();
        $roles = Role::where('active', true)->get();

        $modules = Module::with('permissions')
            ->where('active', true)
            ->get()
            ->groupBy('slug');

        return view('usuarios.create', compact('areas', 'roles', 'modules'));
    }

    /**
     * Guardar usuario nuevo
     */

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['nullable', 'integer'],
            'areas' => ['required', 'array'],
            'permissions' => ['nullable', 'array'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => $data['role_id'] ?? null,
            'active' => true,
        ]);

        // Áreas (nuevo modelo)
        $user->areas()->sync($data['areas']);

        // Permisos directos
        if (!empty($data['permissions'])) {
            $user->permissions()->sync($data['permissions']);
        }

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Formulario de edición
     */


    public function edit($id)
    {
        $user = User::findOrFail($id);

        $areas = Area::where('active', true)->get();
        $roles = Role::where('active', true)->get();

        $modules = Module::with('permissions')
            ->where('active', true)
            ->get()
            ->groupBy('slug');

        // IDs ya asignados
        $userAreaIds = $user->areas->pluck('id')->toArray();
        $userPermissionIds = $user->permissions->pluck('id')->toArray();

        return view('usuarios.edit', compact(
            'user',
            'areas',
            'roles',
            'modules',
            'userAreaIds',
            'userPermissionIds'
        ));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role_id' => ['nullable', 'integer'],
            'areas' => ['required', 'array'],
            'permissions' => ['nullable', 'array'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role_id'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->update([
                'password' => bcrypt($data['password']),
            ]);
        }

        // Sync áreas
        $user->areas()->sync($data['areas']);

        // Sync permisos
        $user->permissions()->sync($data['permissions'] ?? []);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado');
    }
}