@extends('layouts.admin')

@section('title', 'Editar usuario')

@section('content')
<h1>Editar usuario</h1>

<form method="POST" action="{{ route('usuarios.update', $user->id) }}" class="form-inline-responsive">
    @csrf
    @method('PUT')

    {{-- NOMBRE --}}
    <div class="form-group">
        <label>Nombre</label>
        <input
            name="name"
            class="form-control"
            value="{{ $user->name }}"
            required>
    </div>

    {{-- EMAIL --}}
    <div class="form-group">
        <label>Email</label>
        <input
            name="email"
            type="email"
            class="form-control"
            value="{{ $user->email }}"
            required>
    </div>

    {{-- ROL --}}
    <div class="form-group">
        <label>Rol (opcional)</label>
        <select name="role_id" class="form-control">
            <option value="">Sin rol</option>
            @foreach ($roles as $r)
                <option value="{{ $r->id }}"
                    {{ $user->role_id == $r->id ? 'selected' : '' }}>
                    {{ $r->name }}
                </option>
            @endforeach
        </select>
        <small class="form-text text-muted">
            El rol es opcional. Los permisos asignados definen el acceso real.
        </small>
    </div>

    {{-- ÁREAS --}}
    <div class="form-group">
        <label>Áreas</label>
        <select name="areas[]" class="form-control" multiple required>
            @foreach ($areas as $area)
                <option value="{{ $area->id }}"
                    {{ in_array($area->id, $userAreaIds) ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- PASSWORD --}}
    <div class="form-group">
        <label>Nueva contraseña (opcional)</label>
        <input
            type="password"
            name="password"
            class="form-control"
            placeholder="Dejar vacío para no cambiar">

        <small class="form-text text-muted">
            Mínimo 8 caracteres, una mayúscula y un número.
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
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->id }}"
                            id="perm_{{ $permission->id }}"
                            {{ in_array($permission->id, $userPermissionIds) ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="perm_{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <button class="btn btn-success">
        Actualizar
    </button>

</form>
@endsection