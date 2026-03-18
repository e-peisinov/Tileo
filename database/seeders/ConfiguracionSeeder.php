<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $configuraciones = [
            [
                'clave'       => 'modo_vacaciones',
                'valor'       => 'false',
                'tipo'        => 'booleano',
                'etiqueta'    => 'Modo vacaciones',
                'descripcion' => 'Cuando está activo, el checkout muestra un mensaje y no permite confirmar pedidos.',
            ],
            [
                'clave'       => 'mensaje_vacaciones',
                'valor'       => 'Estamos de vacaciones. Volvemos pronto y podrás hacer tu pedido normalmente.',
                'tipo'        => 'texto',
                'etiqueta'    => 'Mensaje de vacaciones',
                'descripcion' => 'Texto que ven los clientes cuando el modo vacaciones está activo.',
            ],
            [
                'clave'       => 'tiempo_entrega',
                'valor'       => '2 a 5 días hábiles',
                'tipo'        => 'texto',
                'etiqueta'    => 'Tiempo de entrega estimado',
                'descripcion' => 'Se muestra en el checkout y en la confirmación del pedido.',
            ],
            [
                'clave'       => 'cbu',
                'valor'       => '',
                'tipo'        => 'texto',
                'etiqueta'    => 'CBU para transferencias',
                'descripcion' => 'Número CBU de 22 dígitos para recibir transferencias.',
            ],
            [
                'clave'       => 'alias_cbu',
                'valor'       => '',
                'tipo'        => 'texto',
                'etiqueta'    => 'Alias CBU',
                'descripcion' => 'Alias de la cuenta bancaria (ejemplo: tileo.especias).',
            ],
            [
                'clave'       => 'titular_cuenta',
                'valor'       => '',
                'tipo'        => 'texto',
                'etiqueta'    => 'Titular de la cuenta',
                'descripcion' => 'Nombre del titular de la cuenta bancaria.',
            ],
        ];

        foreach ($configuraciones as $config) {
            Configuracion::updateOrCreate(
                ['clave' => $config['clave']],
                $config
            );
        }
    }
}
