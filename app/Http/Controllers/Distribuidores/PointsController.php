<?php

namespace App\Http\Controllers\Distribuidores;

use App\Http\Controllers\Controller;
use App\Services\PointsReadService;

class PointsController extends Controller
{
    public function index(PointsReadService $pointsReadService)
    {
        $distributorId = auth('distributor')->id();

        return view('distribuidores.index', [
            'resumen' => $pointsReadService->resumen($distributorId),
            'historial' => $pointsReadService->historial($distributorId),
        ]);
    }
}
