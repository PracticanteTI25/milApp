@extends('layouts.admin')

@section('title', 'Ajustes manuales de puntos')

@section('content')
    <div class="card">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('finanzas.ajustes.store') }}">
                @csrf

                <div class="form-group">
                    <label>Distribuidora (ID)</label>
                    <input type="number" name="distributor_id" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Tipo de ajuste</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="positivo">Sumar puntos</option>
                        <option value="negativo">Restar puntos</option>
                    </select>
                </div>

                <div class="form-group" id="estado-group">
                    <label>Estado inicial (solo positivo)</label>
                    <select name="estado" class="form-control">
                        <option value="congelado">Congelado</option>
                        <option value="habilitado">Habilitado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Puntos</label>
                    <input type="number" name="puntos" class="form-control" min="1" required>
                </div>

                <div class="form-group">
                    <label>Comentario</label>
                    <textarea name="comentario" class="form-control" required></textarea>
                </div>

                <button class="btn btn-primary">Aplicar ajuste</button>
            </form>

        </div>
    </div>
@endsection


//si el tipo es positivo muestra "estado" y si es negativo lo oculta
@push('scripts')
    <script>
        document.getElementById('tipo').addEventListener('change', function () {
            document.getElementById('estado-group').style.display =
                this.value === 'positivo' ? 'block' : 'none';
        });
    </script>
@endpush