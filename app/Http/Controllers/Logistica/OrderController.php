<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PermissionRegistry;

class OrderController extends Controller
{
    /**
     * Listado de pedidos.
     */
    public function index()
    {
        $orders = Order::with('distributor')  //trae todos los pedidos junto con su distribuidor
            ->orderByDesc('created_at')  //ordena los pedidos del mas nuevo al más viejo
            ->get();  //ejecuta la consulta y trae todos los pedidos 

        return view('areas.logistica.pedidos.index', compact('orders'));
    }

    /**
     * Detalle de un pedido.
     */
    public function show(Order $order)     //recibe el ID desde la URL
    {
        $order->load(['items.product', 'distributor']);   //informacion de cada producto, y que distribuidora hizo el pedido (relaciones)

        return view('areas.logistica.pedidos.show', compact('order'));
    }

    /**
     * Generador de pdf
     */
    public function pdf(Order $order)
    {
        $order->load(['items.product', 'distributor']);   //informacion de cada producto, y que distribuidora hizo el pedido (relaciones)

        $pdf = Pdf::loadView('areas.logistica.pedidos.pdf', compact('order'));  //convierte la vista en pdf

        return $pdf->stream("pedido_{$order->id}.pdf");  //genera el pdf, y lo abre en el navegador

        //para cambiar para descargar automaticamente:  return $pdf->download("pedido_{$order->id}.pdf");
    }

    public function __construct()
    {
        PermissionRegistry::register(
            slug: 'logistica.pedidos.gestionar',
            name: 'Gestión de pedidos',
            area: 'logistica'
        );
    }
}
