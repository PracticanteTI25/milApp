@extends('layouts.admin')

@section('title', 'Crear usuario')

@section('content')
    <h1>Crear usuario</h1>

    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        <div class="form-group">
            <label>Nombre</label>
            <input name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input name="password" type="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Rol</label>
            <select name="role_id" class="form-control" required>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label>Área</label>
            <select name="area_id" class="form-control" required>
                <option value="">Seleccione un área</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <button class="btn btn-success">Guardar</button>
    </form>
@endsection