<nav class="flex flex-wrap gap-1 mb-8 bg-white/70 backdrop-blur-sm rounded-2xl p-1.5 border border-[#d4b896]/25 shadow-sm w-fit">
    <a href="{{ route('admin.dashboard') }}" wire:navigate
       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
              {{ request()->routeIs('admin.dashboard') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
        <i class="fa-solid fa-gauge-high text-[10px]"></i> Dashboard
    </a>
    <a href="{{ route('admin.pedidos') }}" wire:navigate
       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
              {{ request()->routeIs('admin.pedidos', 'admin.detalle-pedido') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
        <i class="fa-solid fa-bag-shopping text-[10px]"></i> Pedidos
    </a>
    <a href="{{ route('admin.productos') }}" wire:navigate
       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
              {{ request()->routeIs('admin.productos') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
        <i class="fa-solid fa-seedling text-[10px]"></i> Productos
    </a>
    <a href="{{ route('admin.categorias') }}" wire:navigate
       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
              {{ request()->routeIs('admin.categorias') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
        <i class="fa-solid fa-tags text-[10px]"></i> Categorías
    </a>
    <a href="{{ route('admin.usuarios') }}" wire:navigate
       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
              {{ request()->routeIs('admin.usuarios') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
        <i class="fa-solid fa-users text-[10px]"></i> Usuarios
    </a>
    <a href="{{ route('admin.configuracion') }}" wire:navigate
       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium transition-all duration-200
              {{ request()->routeIs('admin.configuracion') ? 'bg-[#386641] text-white shadow-sm' : 'text-[#8b5e3c] hover:bg-[#f0e9de] hover:text-[#2c1a0e]' }}">
        <i class="fa-solid fa-gear text-[10px]"></i> Config
    </a>
</nav>
