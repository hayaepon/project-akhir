<aside class="fixed top-0 left-0 bg-blue-800 text-white w-64 min-h-screen flex flex-col justify-between rounded-none">
    <!-- Logo -->
    <div class="p-6 font-bold text-lg text-center border-b border-blue-700">
        SPK KIP-K & Tahfiz
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex-1 mt-4 px-4 space-y-2">
        <!-- Menu Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-blue-700">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span>Dashboard</span>
         </a>


        <!-- Menu Data Calon Penerima -->
        <a href="{{ route('admin.calon_penerima.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-blue-700 cursor-pointer text-white">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span>Data Calon Penerima</span>
        </a>


        <!-- Menu Kriteria & Bobot dengan Dropdown -->
        <!-- <div x-data="{ open: false }">
            <button @click.prevent="open = !open" class="w-full flex items-center justify-between py-2 px-3 rounded hover:bg-blue-700">
                <div class="flex items-center space-x-3">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                    <span>Kriteria & Bobot</span>
                </div>
                <i data-lucide="chevron-down" :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform"></i>
            </button>

            Dropdown Submenu 
            <div x-show="open" x-transition class="ml-6 mt-2 space-y-2" x-cloak>
            <a href="{{ route('admin.kriteria.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-blue-700 text-white">
                <i data-lucide="sliders" class="w-5 h-5"></i>
                <span>Kriteria</span>
            </a>

            <a href="{{ route('admin.subkriteria.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-blue-700 text-white">
                <i data-lucide="sliders" class="w-5 h-5"></i>
                <span>Sub Kriteria</span>
            </a>

            </div>
        </div>-->

        <!-- Menu Perhitungan SMART -->
        <a href="{{ route('admin.perhitungan_smart.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-blue-700 cursor-pointer text-white">
            <i data-lucide="calculator" class="w-5 h-5"></i>
            <span>Input Nilai</span>
        </a>


        <!-- Menu Hasil Seleksi 
        <a href="{{ route('admin.Hasil_Seleksi.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-blue-700 cursor-pointer text-white">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span>Hasil Seleksi</span>
        </a>
</nav> -->

    <!-- Tombol Logout -->
<div class="px-4 py-6 flex justify-center">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center space-x-2 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i data-lucide="log-out" class="w-5 h-5 transform scale-x-[-1]"></i>
            <span>Keluar</span>
        </button>
    </form>
</div>
</aside>
