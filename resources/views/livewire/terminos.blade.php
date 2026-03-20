<div class="min-h-screen py-16 px-4 bg-[#faf6f0]">
    <div class="max-w-3xl mx-auto">
        <div class="mb-10">
            <p class="text-[#8b5e3c]/70 tracking-[0.3em] uppercase text-[11px] font-medium mb-3">Legal</p>
            <h1 class="text-5xl text-[#2c1a0e]" style="font-family: 'DM Serif Display', serif;">Términos y Condiciones</h1>
            <p class="text-sm text-[#8b5e3c]/60 mt-3">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="space-y-8 text-[#2c1a0e]/80 leading-relaxed text-sm">
            <section>
                <h2 class="text-2xl text-[#2c1a0e] mb-3" style="font-family: 'DM Serif Display', serif;">1. Aceptación de los términos</h2>
                <p>Al acceder y utilizar el sitio web de Tileo, aceptás cumplir y estar sujeto a los siguientes términos y condiciones de uso. Si no estás de acuerdo con alguno de estos términos, te pedimos que no utilices nuestro sitio.</p>
            </section>
            <section>
                <h2 class="text-2xl text-[#2c1a0e] mb-3" style="font-family: 'DM Serif Display', serif;">2. Descripción del servicio</h2>
                <p>Tileo es un emprendimiento dedicado a la venta de hierbas, especias y condimentos artesanales ubicado en Mercedes, Buenos Aires, Argentina. A través de nuestro sitio web, los clientes pueden explorar nuestro catálogo y realizar pedidos online.</p>
            </section>
            <section>
                <h2 class="text-2xl text-[#2c1a0e] mb-3" style="font-family: 'DM Serif Display', serif;">3. Pedidos y pagos</h2>
                <p>Al realizar un pedido, te comprometés a proporcionar información veraz y completa. Los pedidos están sujetos a disponibilidad de stock. Aceptamos pagos en efectivo y transferencia bancaria. El costo de envío, cuando aplica, será confirmado por nuestro equipo.</p>
            </section>
            <section>
                <h2 class="text-2xl text-[#2c1a0e] mb-3" style="font-family: 'DM Serif Display', serif;">4. Entrega</h2>
                <p>Ofrecemos retiro en local y envío a domicilio. Los tiempos de entrega son estimativos y pueden variar. Nos comprometemos a informarte sobre el estado de tu pedido en todo momento.</p>
            </section>
            <section>
                <h2 class="text-2xl text-[#2c1a0e] mb-3" style="font-family: 'DM Serif Display', serif;">5. Privacidad de datos</h2>
                <p>Los datos personales que nos proporcionás son utilizados exclusivamente para procesar y gestionar tus pedidos. No compartimos tu información con terceros. Podés consultar nuestra <a href="{{ route('privacidad') }}" wire:navigate class="text-[#386641] hover:underline">Política de Privacidad</a> para más detalles.</p>
            </section>
            <section>
                <h2 class="text-2xl text-[#2c1a0e] mb-3" style="font-family: 'DM Serif Display', serif;">6. Cambios y cancelaciones</h2>
                <p>Podés cancelar tu pedido antes de que pase al estado "Preparando". Una vez en preparación, no es posible modificarlo. Ante cualquier inconveniente, contactanos por <a href="{{ route('contacto') }}" wire:navigate class="text-[#386641] hover:underline">contacto</a>.</p>
            </section>
            <section>
                <h2 class="text-2xl text-[#2c1a0e] mb-3" style="font-family: 'DM Serif Display', serif;">7. Contacto</h2>
                <p>Para consultas sobre estos términos, usá nuestro <a href="{{ route('contacto') }}" wire:navigate class="text-[#386641] hover:underline">formulario de contacto</a>.</p>
            </section>
        </div>
    </div>
</div>
