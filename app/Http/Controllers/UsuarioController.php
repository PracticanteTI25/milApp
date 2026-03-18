<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Listar
    public function index()
    {
        $usuarios = Usuario::all();

        return view('usuarios.index', compact('usuarios'));
    }

    // Crear vista
    public function create()
    {
        return view('usuarios.create');
    }

    // Guardar
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:6',
            'rol' => 'required'
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'password' => Hash::make($request->password), // encriptación
            'rol' => $request->rol
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    // Editar
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        return view('usuarios.edit', compact('usuario'));
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'correo' => 'required|email|unique:usuarios,correo,' . $id,
            'rol' => 'required'
        ]);

        $datos = [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'rol' => $request->rol
        ];

        // SOLO si se escribe contraseña
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        return redirect('/usuarios')->with('success', 'Usuario actualizado correctamente');
    }

    // Eliminar
    public function destroy($id)
    {
        Usuario::destroy($id);

        return redirect('/usuarios')->with('success', 'Usuario eliminado correctamente');
    }
}