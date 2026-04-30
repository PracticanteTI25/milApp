<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Module;
use App\Models\Permission;


class UsuarioController extends Controller
{
    /**
     * Listado de usuarios
     */

    public function index()
    {
        // Cargamos relaciones para evitar N+1
        $usuarios = User::with(['role', 'areas'])
            ->orderBy('name')
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario de creación
     */

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $areas = Area::orderBy('name')->get();

        //  SOLO módulos migrados a permisos funcionales
        $modules = Module::whereIn('slug', [
            'comercial',
        ])->orderBy('name')->get();

        // SOLO permisos funcionales (no CRUD legacy)
        $functionalPermissions = Permission::whereIn('module_id', $modules->pluck('id'))
            ->whereNotIn('slug', [
                // CRUD legacy
                'ver',
                'crear',
                'editar',
                'eliminar',

                // PERMISOS LEGACY CONFLICTIVOS
                'registrar_distribuidoras',
                'gestionar_productos',
                'asignar_puntos',
            ])
            ->orderBy('slug')
            ->get()
            ->groupBy('module_id');

        return view('usuarios.create', compact(
            'roles',
            'areas',
            'modules',
            'functionalPermissions'
        ));
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
            'roles' => ['nullable', 'array'],
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


        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

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
        $user = User::with(['areas', 'permissions', 'role'])->findOrFail($id);

        $roles = Role::orderBy('name')->get();
        $areas = Area::orderBy('name')->get();

        // SOLO módulos migrados a permisos funcionales
        $modules = Module::whereIn('slug', [
            'comercial',
        ])->orderBy('name')->get();

        // SOLO permisos funcionales (no CRUD legacy)
        $functionalPermissions = Permission::whereIn('module_id', $modules->pluck('id'))
            ->whereNotIn('slug', [
                // CRUD legacy
                'ver',
                'crear',
                'editar',
                'eliminar',

                // PERMISOS LEGACY CONFLICTIVOS
                'registrar_distribuidoras',
                'gestionar_productos',
                'asignar_puntos',
            ])
            ->orderBy('slug')
            ->get()
            ->groupBy('module_id');

        // IDs de áreas del usuario (para marcar checkboxes)
        $userAreaIds = $user->areas->pluck('id')->toArray();

        // IDs de permisos funcionales del usuario
        $userPermissionIds = $user->permissions->pluck('id')->toArray();

        return view('usuarios.edit', compact(
            'user',
            'roles',
            'areas',
            'modules',
            'functionalPermissions',
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
            'roles' => ['nullable', 'array'],
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

        $user->roles()->sync($data['roles'] ?? []);

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
