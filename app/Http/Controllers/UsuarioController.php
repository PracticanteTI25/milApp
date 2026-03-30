<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios
     */
    public function index()
    {
        $usuarios = User::with(['role', 'area'])->get();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $roles = Role::where('active', true)->get();
        $areas = Area::where('active', true)->get();

        return view('usuarios.create', compact('roles', 'areas'));
    }

    /**
     * Guardar usuario nuevo
     */
    public function store(Request $request)
    {
        // ✅ Validación (OWASP: input validation)
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'area_id' => 'required|exists:areas,id',
        ]);

        // ✅ Crear usuario real
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'area_id' => $data['area_id'],
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::where('active', true)->get();
        $areas = Area::where('active', true)->get();

        return view('usuarios.edit', compact('usuario', 'roles', 'areas'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'area_id' => 'required|exists:areas,id',
        ]);

        $usuario->update($data);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado');
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