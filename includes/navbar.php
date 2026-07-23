<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="bg-white shadow-lg fixed w-full z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?= BASE_URL ?>" class="flex items-center">
                    <span class="text-2xl font-bold" style="color: #2F5233;">Gunung Bismo</span>
                    <span class="text-sm ml-1" style="color: #4A7A4E;">Deroduwur</span>
                </a>
            </div>

            <!-- Navbar Desktop -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="<?= BASE_URL ?>" class="<?= $current_page == 'index.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?> transition duration-300">
                    Beranda
                </a>
                
                <!-- Dropdown Kisah Kami -->
                <div class="relative group">
                    <a href="#" class="text-[#5C5C50] hover:text-[#2F5233] transition duration-300 flex items-center">
                        Kisah Kami
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <a href="<?= BASE_URL ?>kisah.php" class="block px-4 py-2 text-[#5C5C50] hover:bg-[#FAF7F2] hover:text-[#2F5233] rounded-t-lg">Tentang Kami</a>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>jalur.php" class="<?= $current_page == 'jalur.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?> transition duration-300">
                    Telusur Jalur
                </a>

                <!-- Dropdown Siap Mendaki -->
                <div class="relative group">
                    <a href="#" class="text-[#5C5C50] hover:text-[#2F5233] transition duration-300 flex items-center">
                        Siap Mendaki
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <a href="<?= BASE_URL ?>persiapan.php" class="block px-4 py-2 text-[#5C5C50] hover:bg-[#FAF7F2] hover:text-[#2F5233] rounded-t-lg">Persiapan & Logistik</a>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>alam.php" class="<?= $current_page == 'alam.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?> transition duration-300">
                    Alam Bismo
                </a>
                <a href="<?= BASE_URL ?>galeri.php" class="<?= $current_page == 'galeri.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?> transition duration-300">
                    Jejak Visual
                </a>
                <a href="<?= BASE_URL ?>berita.php" class="<?= $current_page == 'berita.php' || $current_page == 'berita-detail.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?> transition duration-300">
                    Kabar Bismo
                </a>
                <a href="<?= BASE_URL ?>kontak.php" class="<?= $current_page == 'kontak.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?> transition duration-300">
                    Temui Kami
                </a>
                
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-6 py-2 rounded-full transition duration-300 font-medium">
                    Hubungi via WA
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden text-[#2F5233] focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden pb-4">
            <a href="<?= BASE_URL ?>" class="block py-2 <?= $current_page == 'index.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?>">Beranda</a>
            <a href="<?= BASE_URL ?>kisah.php" class="block py-2 <?= $current_page == 'kisah.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?>">Kisah Kami</a>
            <a href="<?= BASE_URL ?>jalur.php" class="block py-2 <?= $current_page == 'jalur.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?>">Telusur Jalur</a>
            <a href="<?= BASE_URL ?>persiapan.php" class="block py-2 <?= $current_page == 'persiapan.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?>">Siap Mendaki</a>
            <a href="<?= BASE_URL ?>alam.php" class="block py-2 <?= $current_page == 'alam.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?>">Alam Bismo</a>
            <a href="<?= BASE_URL ?>galeri.php" class="block py-2 <?= $current_page == 'galeri.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?>">Jejak Visual</a>
            <a href="<?= BASE_URL ?>berita.php" class="block py-2 <?= $current_page == 'berita.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?>">Kabar Bismo</a>
            <a href="<?= BASE_URL ?>kontak.php" class="block py-2 <?= $current_page == 'kontak.php' ? 'text-[#2F5233] font-bold' : 'text-[#5C5C50] hover:text-[#2F5233]' ?>">Temui Kami</a>
            <a href="https://wa.me/6281234567890" target="_blank" class="block py-2 text-[#E0BE45] font-bold">Hubungi via WA</a>
        </div>
    </div>
</nav>