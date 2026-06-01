@extends('layouts.admin')

@section('title', 'Devoluciones - Calidad')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <h1>Gestión de Devoluciones</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <div class="card">

            <div class="card-body">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Distribuidor</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Imagen</th>
                            <th>Observaciones</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($devoluciones as $dev)
                        <tr>
                            <td>{{ $dev->id }}</td>

                            <td>{{ $dev->distributor->name ?? 'N/A' }}</td>

                            <td>{{ $dev->lote }}</td>

                            <td>{{ $dev->cantidad }}</td>

                            <td>
                                @if($dev->imagen_path)
                                <img src="{{ asset('storage/' . $dev->imagen_path) }}"
                                    style="width:60px; height:60px; object-fit:cover; border-radius:8px;">

                                @else
                                —
                                @endif
                            </td>

                            <td>{{ $dev->observaciones }}</td>

                            <td>
                                <span class="badge badge-info">
                                    {{ $dev->estado }}
                                </span>
                            </td>

                            <td>{{ $dev->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">No hay devoluciones registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>

    </div>
</section>

@endsection