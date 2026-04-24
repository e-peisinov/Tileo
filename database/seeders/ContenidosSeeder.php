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
            ['pregunta' => '¿Cómo puedo hacer un pedido?', 'respuesta' => 'Los pedidos se realizan por WhatsApp. Explorá nuestro catálogo, elegí los productos que te interesan y escribinos directamente. El botón de WhatsApp está disponible en todas las páginas del sitio.'],
            ['pregunta' => '¿Hacen envíos?', 'respuesta' => 'Sí, enviamos a domicilio. El costo y la modalidad de envío se coordinan por WhatsApp al momento de confirmar el pedido.'],
            ['pregunta' => '¿Puedo retirar en persona?', 'respuesta' => 'Sí, podés retirar sin costo en Mercedes, Buenos Aires. Coordinamos día y horario por WhatsApp.'],
            ['pregunta' => '¿Cuáles son las formas de pago?', 'respuesta' => 'Aceptamos efectivo y transferencia bancaria. Te compartimos los datos al confirmar el pedido.'],
            ['pregunta' => '¿Los productos tienen fecha de vencimiento?', 'respuesta' => 'Sí, todos nuestros productos tienen fecha de vencimiento indicada en el envase.'],
            ['pregunta' => '¿Puedo pedir productos que no aparecen en el catálogo?', 'respuesta' => 'Sí, consultanos por WhatsApp o a través del formulario de contacto. Trabajamos con productores locales y podemos orientarte sobre disponibilidad.'],
            ['pregunta' => '¿Los productos son aptos para personas con alergias?', 'respuesta' => 'Consultanos por WhatsApp antes de hacer tu pedido indicándonos tu alergia. Te asesoramos sobre la composición de cada producto.'],
            ['pregunta' => '¿En qué se diferencian los productos Tileo de los de supermercado?', 'respuesta' => 'Son elaborados artesanalmente, sin conservantes ni procesos industriales, y envasados en tubos de vidrio con tapa de corcho. Cada producto es seleccionado y preparado a mano.'],
        ];
        Contenido::updateOrCreate(['clave' => 'preguntas_frecuentes'], [
            'etiqueta' => 'Preguntas frecuentes',
            'tipo'     => 'json_faq',
            'cuerpo'   => json_encode($faqs, JSON_UNESCAPED_UNICODE),
        ]);
    }
}
