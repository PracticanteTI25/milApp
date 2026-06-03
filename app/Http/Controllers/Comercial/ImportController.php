<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DistributorImport;
use App\Imports\AddressImport;
use App\Imports\SalesImport;

class ImportController extends Controller
{
    public function index()
    {
        return view('areas.comercial.importaciones.index');
    }

    public function store(Request $request)
    {

        $request->validate([
            'type' => 'required|in:distributors,addresses,sales',
            'file' => 'required|file|mimes:xlsx,xls|max:20480'
        ]);

        try {

            switch ($request->type) {

                case 'distributors':
                    Excel::import(new DistributorImport, $request->file('file'));
                    break;

                case 'addresses':
                    Excel::import(new AddressImport, $request->file('file'));
                    break;

                case 'sales':
                    Excel::import(new SalesImport, $request->file('file'));
                    break;
            }

            return back()->with('success_' . $request->type, 'Importación realizada correctamente');
        } catch (\Exception $e) {

            return back()->with('error_' . $request->type, 'Error al procesar el archivo');

        }
    }
}
