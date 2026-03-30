<?php

namespace App\Http\Controllers;

class ReporteController extends Controller
{
    /**
     * Fuente temporal de dashboards.
     * En el futuro esto vendrá de BD + permisos.
     */
    private array $dashboards = [
        'rendimiento-general' => [
            'title' => 'Rendimiento General',
            'description' => 'Indicadores clave de la organización',
            'icon' => 'fas fa-chart-line',
            'url' => 'https://app.powerbi.com/reportEmbed?reportId=0a56f419-5dcd-47ce-a281-c3869081d431&autoAuth=true&ctid=39531c95-7f29-4336-84ae-a08a52c160dc',
        ],

        // Ejemplo para el futuro:
        // 'ventas' => [
        //     'title' => 'Ventas',
        //     'description' => 'Comportamiento comercial',
        //     'icon' => 'fas fa-dollar-sign',
        //     'url' => 'https://...',
        // ],
    ];

    public function index()
    {
        return view('reportes.index', [
            'dashboards' => $this->dashboards,
        ]);
    }

    public function show(string $id)
    {
        if (!isset($this->dashboards[$id])) {
            abort(404);
        }

        return view('reportes.show', [
            'dashboard' => $this->dashboards[$id],
        ]);
    }
}