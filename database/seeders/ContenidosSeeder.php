<?php
namespace Database\Seeders;
use App\Models\Contenido;
use Illuminate\Database\Seeder;

class ContenidosSeeder extends Seeder
{
    public function run(): void
    {
        // Nosotros - historia
        Contenido::updateOrCreate(['clave' => 'nosotros_historia'], [
            'etiqueta' => 'Historia de Tileo',
            'tipo'     => 'html',
            'cuerpo'   => '<p>Somos un emprendimiento familiar de Mercedes, Buenos Aires, apasionados por las hierbas, especias y condimentos artesanales.</p>',
        ]);

        // FAQs
        $faqs = [
            ['pregunta' => '¿Cómo puedo hacer un pedido?', 'respuesta' => 'Navegá por nuestro catálogo, agregá los productos que quieras al carrito y completá el checkout. Recibirás una confirmación por email.'],
            ['pregunta' => '¿Cuáles son las formas de pago?', 'respuesta' => 'Aceptamos efectivo y transferencia bancaria.'],
            ['pregunta' => '¿Hacen envíos?', 'respuesta' => 'Sí, enviamos a domicilio. El costo de envío se coordina al confirmar el pedido.'],
            ['pregunta' => '¿Puedo retirar en el local?', 'respuesta' => 'Sí, podés retirar sin costo en nuestro local de Mercedes, Buenos Aires.'],
            ['pregunta' => '¿Cómo sé que mi pedido fue recibido?', 'respuesta' => 'Recibirás un email de confirmación con el detalle del pedido.'],
            ['pregunta' => '¿Puedo cancelar mi pedido?', 'respuesta' => 'Sí, podés cancelarlo comunicándote por WhatsApp antes de que sea preparado.'],
            ['pregunta' => '¿Los productos tienen fecha de vencimiento?', 'respuesta' => 'Todos nuestros productos tienen fecha de vencimiento indicada en el envase.'],
            ['pregunta' => '¿Tienen productos orgánicos?', 'respuesta' => 'Trabajamos con productores locales y procuramos ingredientes de origen conocido. Consultanos por opciones específicas.'],
        ];
        Contenido::updateOrCreate(['clave' => 'preguntas_frecuentes'], [
            'etiqueta' => 'Preguntas frecuentes',
            'tipo'     => 'json_faq',
            'cuerpo'   => json_encode($faqs, JSON_UNESCAPED_UNICODE),
        ]);
    }
}
