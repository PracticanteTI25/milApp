<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $order->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h1 {
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1>Pedido #{{ $order->id }}</h1>

    <p><strong>Distribuidora:</strong> {{ $order->distributor->name }}</p>
    <p><strong>Fecha:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Total puntos:</strong> {{ $order->total_points }}</p>

    <h3>Productos</h3>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Puntos unitarios</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->points }}</td>
                    <td>{{ $item->points * $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>