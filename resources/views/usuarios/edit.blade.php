@extends('layouts.admin')

@section('title', 'Editar usuario')

@section('content')
<h1>Editar usuario</h1>

<form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nombre</label>
        <input name="name" class="form-control"
               value="{{ $usuario->name }}" required>
    </div>

    <div class="form-group">
        <label>Rol</label>
        <select name="role_id" class="form-control">
            @foreach($roles as $r)
                <option value="{{ $r->id }}"
                    @if($usuario->role_id == $r->id) selected @endif>
                    {{ $r->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Área</label>
        <select name="area_id" class="form-control">
            @foreach($areas as $a)
                <option value="{{ $a->id }}"
                    @if($usuario->area_id == $a->id) selected @endif>
                    {{ $a->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-success">Actualizar</button>
</form>
@endsection