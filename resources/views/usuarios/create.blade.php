@extends('layouts.admin')

@section('title', 'Crear usuario')

@section('content')
    <h1>Crear usuario</h1>

    <form method="POST" action="{{ route('usuarios.store') }}" class="form-inline-responsive">
        @csrf

        {{-- NOMBRE --}}
        <div class="form-group">
            <label>Nombre</label>
            <input name="name" class="form-control" required>
        </div>

        {{-- EMAIL --}}
        <div class="form-group">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>

        {{-- PASSWORD --}}
        <div class="form-group">
            <label>Contraseña</label>
            <input name="password" type="password" class="form-control" required>

            <small class="form-text text-muted">
                La contraseña debe tener al menos 8 caracteres, una letra mayúscula y un número.
            </small>
        </div>

        {{-- ROL --}}
        <div class="form-group">
            <label>Rol</label>
            <small class="form-text text-muted">
                El rol es opcional. Los permisos asignados definirán el acceso real.
            </small>
        </div>

        {{-- ÁREAS (MULTIPLE) --}}
        <div class="form-group">
            <label>Áreas</label>
            <select name="areas[]" class="form-control" multiple required>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">
                El usuario puede pertenecer a una o varias áreas.
            </small>
        </div>

        <hr>

        {{-- PERMISOS --}}
        <h5>Permisos</h5>

        @foreach ($modules as $moduleSlug => $moduleGroup)
            @php $module = $moduleGroup->first(); @endphp

            <div class="card mb-3">
                <div class="card-header">
                    {{ $module->name }}
                </div>

                <div class="card-body">
                    @foreach ($module->permissions as $permission)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                id="perm_{{ $permission->id }}">
                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button class="btn btn-success">Guardar</button>
    </form>
@endsection