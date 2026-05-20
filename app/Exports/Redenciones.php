<?php

namespace App\Exports;

use App\Models\Redencion;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Redenciones implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        // Obtener todos los productos del catálogo
        $products = Product::orderBy('id')->get();

        // Obtener todas las redenciones con relaciones
        $redenciones = Redencion::with([
            'distributor',
            'direccion',
            'productos.product',
        ])
            ->whereHas('productos') // SOLO redenciones con productos
            ->orderBy('fecha')
            ->get();

        $rows = collect();

        foreach ($redenciones as $redencion) {

            // Fila base (datos fijos)
            $row = [
                'NIT' => $redencion->document_snapshot ?? '',
                'NOMBRE VENDEDORES' => $redencion->nombre_snapshot ?? '',
                'DIRECCIÓN' => $redencion->direccion_snapshot ?? '',
                'MUNICIPIO' => $redencion->municipio_snapshot ?? '',
                'CELULAR' => $redencion->telefono_snapshot ?? '',
            ];

            // Inicializar columnas de productos en 0
            foreach ($products as $product) {
                $row[$product->name] = 0;
            }

            // Llenar cantidades reales
            foreach ($redencion->productos as $detalle) {
                $row[$detalle->product->name] = $detalle->cantidad;
            }

            $rows->push($row);
        }

        return $rows;
    }

    public function headings(): array
    {
        // Columnas fijas
        $headings = [
            'NIT',
            'NOMBRE VENDEDORES',
            'DIRECCIÓN',
            'MUNICIPIO',
            'CELULAR',
        ];

        // Columnas de productos
        $products = Product::orderBy('id')->get();
        foreach ($products as $product) {
            $headings[] = $product->name;
        }

        return $headings;
    }
}
