<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;

class SyncProductsFromApi extends Command
{
    // Nombre del comando
    protected $signature = 'products:sync-api';

    // Descripción
    protected $description = 'Sincroniza productos desde la API externa (simulada)';

    public function handle(): int
    {
        $this->info('Iniciando sincronización de productos desde API simulada...');

        $filePath = storage_path('app/fake_api/products.json');

        if (!file_exists($filePath)) {
            $this->error('Archivo products.json no encontrado en: ' . $filePath);
            return Command::FAILURE;
        }

        $json = file_get_contents($filePath);
        
        $products = json_decode($json, true);

        if (!is_array($products)) {
            $this->error('El JSON de productos no es válido.');
            return Command::FAILURE;
        }

        foreach ($products as $item) {

            // Validación mínima (OWASP: input validation)
            if (empty($item['external_id']) || empty($item['name'])) {
                $this->warn('Producto inválido, se omite.');
                continue;
            }

            // Buscar por external_id
            $product = Product::where('external_id', $item['external_id'])->first();

            $data = [
                'name'         => $item['name'],
                'slug'         => Str::slug($item['name']),
                'description'  => $item['description'] ?? null,
                'presentation' => $item['presentation'] ?? null,
                'image_path'   => $item['image_url'] ?? null,
                'active'       => (bool) ($item['active'] ?? true),
                'source'       => 'api',
                'external_id'  => $item['external_id'],
            ];

            if ($product) {
                // Update (NO toca stock ni precios)
                $product->update($data);
                $this->info("Producto actualizado: {$product->name}");
            } else {
                // Create
                Product::create($data);
                $this->info("Producto creado: {$data['name']}");
            }
        }

        $this->info('Sincronización de productos finalizada.');
        return Command::SUCCESS;
    }
}
