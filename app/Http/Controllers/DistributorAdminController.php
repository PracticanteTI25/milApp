<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\DistributorAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// CRUD DE DISTRIBUIDORAS

/**
 * CRUD Distribuidoras (Área Comercial)
 *
 * Seguridad (OWASP):
 * - Validación de entradas
 * - Hash de contraseña
 * - No SQL manual (Eloquent)
 * - Evita modificar por URL sin existir (findOrFail)
 */
class DistributorAdminController extends Controller
{
    /**
     * Listado de distribuidoras.
     */
    public function index()
    {
        $distributors = Distributor::with('address')   //$distributors, es la lista que viene de la BD
            ->orderByDesc('id')
            ->get();

        return view('distribuidores.index', compact('distributors'));  //compact('distributors'), envía esa lista a la vista
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('distribuidores.create');
    }

    /**
     * Guardar nueva distribuidora + dirección principal.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            // Tabla distributors (ya la tienes)
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:distributors,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'active' => ['nullable', 'boolean'],

            // Dirección (tabla distributor_addresses)
            'country' => ['required', 'string', 'max:120'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        // Crear distribuidora
        $dist = Distributor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'active' => $request->boolean('active', true),
        ]);

        // Crear dirección principal
        DistributorAddress::create([
            'distributor_id' => $dist->id,
            'country' => $data['country'],
            'address_line1' => $data['address_line1'],
            'address_line2' => $data['address_line2'] ?? null,
            'city' => $data['city'],
            'state' => $data['state'],
            'postal_code' => $data['postal_code'] ?? null,
            'phone' => $data['phone'] ?? null,
            'is_default' => true,
        ]);

        return redirect()
            ->route('distribuidores.index')
            ->with('success', 'Distribuidora creada correctamente.');
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $distributor = Distributor::with('address')->findOrFail($id);

        return view('distribuidores.edit', compact('distributor'));
    }

    /**
     * Actualizar distribuidora + dirección.
     * Nota: contraseña solo se actualiza si se envía.
     */
    public function update(Request $request, $id)
    {
        $distributor = Distributor::with('address')->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:distributors,email,' . $distributor->id],
            'active' => ['nullable', 'boolean'],

            // contraseña opcional en edición
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],

            // Dirección
            'country' => ['required', 'string', 'max:120'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        // Actualizar distribuidora
        $distributor->name = $data['name'];
        $distributor->email = $data['email'];
        $distributor->active = (bool) $request->input('active');

        // Si envían password, se actualiza
        if (!empty($data['password'])) {
            $distributor->password = Hash::make($data['password']);
        }

        $distributor->save();

        // Actualizar / crear dirección principal
        $address = $distributor->address;

        if (!$address) {
            $address = new DistributorAddress();
            $address->distributor_id = $distributor->id;
            $address->is_default = true;
        }

        $address->country = $data['country'];
        $address->address_line1 = $data['address_line1'];
        $address->address_line2 = $data['address_line2'] ?? null;
        $address->city = $data['city'];
        $address->state = $data['state'];
        $address->postal_code = $data['postal_code'] ?? null;
        $address->phone = $data['phone'] ?? null;

        $address->save();

        return redirect()
            ->route('distribuidores.index')
            ->with('success', 'Distribuidora actualizada correctamente.');
    }

    /**
     * Eliminar distribuidora (y su dirección por cascade).
     */
    public function destroy($id)
    {
        $distributor = Distributor::findOrFail($id);
        $distributor->delete();

        return redirect()
            ->route('distribuidores.index')
            ->with('success', 'Distribuidora eliminada correctamente.');
    }
}
