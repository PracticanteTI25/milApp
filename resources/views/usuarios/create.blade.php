@extends('layouts.admin')

@section('title', 'Crear usuario')

@section('content')
<h1>Crear usuario</h1>

<form method="POST" action="{{ route('usuarios.store') }}">
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
    </div>

    {{-- ROLES --}}
    <h5>Roles</h5>

    @foreach ($roles as $role)
    <div class="form-check">
        <input
            class="form-check-input"
            type="checkbox"
            name="roles[]"
            value="{{ $role->id }}"
            id="role_{{ $role->id }}">
        <label class="form-check-label" for="role_{{ $role->id }}">
            {{ $role->name }}
        </label>
    </div>
    @endforeach

    {{-- ÁREAS --}}
    <h5>Áreas</h5>
    @foreach ($areas as $area)
    <div class="form-check">
        <input
            class="form-check-input"
            type="checkbox"
            name="areas[]"
            value="{{ $area->id }}"
            id="area_{{ $area->id }}">
        <label class="form-check-label" for="area_{{ $area->id }}">
            {{ $area->name }}
        </label>
    </div>
    @endforeach

    <hr>

    {{-- PERMISOS FUNCIONALES --}}
    <h5>Funcionalidades</h5>

    @foreach ($modules as $module)
    @if (isset($functionalPermissions[$module->id]))
    <div class="card mb-3">
        <div class="card-header">{{ $module->name }}</div>
        <div class="card-body">
            @foreach ($functionalPermissions[$module->id] as $permission)
            <div class="form-check">
                <input
                    class="form-check-input permission-checkbox"
                    type="checkbox"
                    name="permissions[]"
                    value="{{ $permission->id }}"
                    data-slug="{{ $module->slug }}.{{ $permission->slug }}"
                    id="perm_{{ $permission->id }}">
                <label class="form-check-label" for="perm_{{ $permission->id }}">
                    {{ $permission->name }}
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach

    <button class="btn btn-success">Guardar</button>
</form>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const checkboxes = document.querySelectorAll('.permission-checkbox');

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {

                if (!this.checked) return;

                const slug = this.dataset.slug;
                if (!slug) return;

                // Si marca crear / editar / eliminar → marcar automáticamente ".ver"
                if (
                    slug.endsWith('.crear') ||
                    slug.endsWith('.editar') ||
                    slug.endsWith('.eliminar')
                ) {
                    const viewSlug = slug.replace(/(\.crear|\.editar|\.eliminar)$/, '.ver');

                    checkboxes.forEach(other => {
                        if (other.dataset.slug === viewSlug) {
                            other.checked = true;
                        }
                    });
                }
            });
        });
    });
</script>
@endpush