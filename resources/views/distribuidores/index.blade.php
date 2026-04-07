@extends('layouts.admin')

@section('title', 'Distribuidoras')

@section('content')
    <h1 class="mb-3">Distribuidoras</h1>

    <a href="{{ route('comercial.distribuidores.create') }}" class="btn btn-primary mb-3">
        + Registrar distribuidora
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Ciudad</th>
                    <th>Departamento</th>
                    <th>Activo</th>
                    <th style="width:160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($distributors as $d)
                    <tr>
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->email }}</td>
                        <td>{{ optional($d->address)->city ?? '-' }}</td>
                        <td>{{ optional($d->address)->state ?? '-' }}</td>
                        <td>{{ $d->active ? 'Sí' : 'No' }}</td>
                        <td>
                            <a href="{{ route('comercial.distribuidores.edit', $d->id) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>

                            <form action="{{ route('comercial.distribuidores.destroy', $d->id) }}" method="POST"
                                style="display:inline-block"
                                onsubmit="return confirm('¿Seguro que deseas eliminar esta distribuidora?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay distribuidoras registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection