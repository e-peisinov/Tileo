<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $especias  = Categoria::create(['nombre' => 'Especias',  'descripcion' => 'Especias naturales y aromáticas.']);
        $picantes  = Categoria::create(['nombre' => 'Picantes',  'descripcion' => 'Variedades picantes y ahumadas.']);
        $hierbas   = Categoria::create(['nombre' => 'Hierbas',   'descripcion' => 'Hierbas aromáticas frescas y secas.']);
        $mezclas   = Categoria::create(['nombre' => 'Mezclas',   'descripcion' => 'Combinaciones artesanales de especias.']);

        $productos = [
            [
                'categoria_id' => $especias->id,
                'nombre'       => 'Nuez Moscada',
                'descripcion'  => 'De aroma intenso y sabor cálido, la nuez moscada es un clásico de la cocina. Ideal para bechameles, purés, pastas y postres.',
                'precio'       => 0,
                'stock'        => 10,
                'unidad'       => 'frasco',
                'imagen'       => 'WhatsApp Image 2026-03-17 at 13.23.44.jpeg',
            ],
            [
                'categoria_id' => $especias->id,
                'nombre'       => 'Paprika',
                'descripcion'  => 'Elaborada con pimientos rojos secos y molidos. Aporta color vibrante y sabor ahumado. Perfecta para carnes, arroces y marinadas.',
                'precio'       => 0,
                'stock'        => 10,
                'unidad'       => 'frasco',
                'imagen'       => 'WhatsApp Image 2026-03-17 at 13.23.44 (3).jpeg',
            ],
            [
                'categoria_id' => $especias->id,
                'nombre'       => 'Pimienta Negra',
                'descripcion'  => 'La reina de las especias. Clásica e imprescindible, realza el sabor de carnes, salsas, ensaladas y todo tipo de preparaciones saladas.',
                'precio'       => 0,
                'stock'        => 10,
                'unidad'       => 'frasco',
                'imagen'       => 'WhatsApp Image 2026-03-17 at 13.23.44 (4).jpeg',
            ],
            [
                'categoria_id' => $picantes->id,
                'nombre'       => 'Ají Molido Merkén',
                'descripcion'  => 'Mezcla mapuche tradicional con ají cacho de cabra ahumado y cilantro tostado. Sabor profundo, picante moderado y aroma único.',
                'precio'       => 0,
                'stock'        => 10,
                'unidad'       => 'frasco',
                'imagen'       => 'WhatsApp Image 2026-03-17 at 13.23.45.jpeg',
            ],
            [
                'categoria_id' => $especias->id,
                'nombre'       => 'Pimentón Dulce',
                'descripcion'  => 'Suave, aromático y con un hermoso color rojo intenso. Aporta dulzura y profundidad a guisos, empanadas, chorizo y cualquier plato casero.',
                'precio'       => 0,
                'stock'        => 10,
                'unidad'       => 'frasco',
                'imagen'       => 'WhatsApp Image 2026-03-17 at 13.23.45 (2).jpeg',
            ],
        ];

        foreach ($productos as $datos) {
            Producto::create($datos);
        }
    }
}
