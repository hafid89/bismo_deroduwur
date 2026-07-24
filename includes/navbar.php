<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="bg-forest-light shadow-lg fixed w-full z-50">
    <div class="w-full px-4 lg:px-8">
        <div class="flex items-center h-20">
            
            <!-- Logo & Brand Name -->
            <div class="flex items-center shrink-0 mr-8 lg:mr-12">
                <a href="<?= BASE_URL ?>" class="flex items-center space-x-2.5 group">
                    <!-- LOGO BULAT -->
                    <div class="w-11 h-11 rounded-full overflow-hidden flex items-center justify-center shrink-0 border border-white/30 shadow-sm">
                        <img 
                            src="assets/images/logo.jpg" 
                            alt="Logo Gunung Bismo" 
                            class="w-full h-full object-cover scale-110"
                        >
                    </div>
                    
                    <!-- Text Brand -->
                    <div class="flex items-center space-x-1.5">
                        <div class="flex flex-col text-white uppercase font-bold leading-[0.85] text-left">
                            <span class="text-[11px] tracking-[0.24em]">GUNUNG</span>
                            <span class="text-[11px] tracking-[0.59em] mt-[3px]">BISMO</span>
                        </div>

                        <div class="h-7 w-[1.5px] bg-white/30 mx-0.5"></div>

                        <div class="flex flex-col text-[#E0BE45] leading-[0.9] text-left">
                            <span class="text-[9px] font-medium tracking-wide uppercase">Via</span>
                            <span class="text-[11px] font-semibold tracking-wider mt-[2px]">Deroduwur</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Navbar Desktop -->
            <div class="hidden xl:flex items-center space-x-4 2xl:space-x-6 text-sm flex-1 justify-between">
                <div class="flex items-center space-x-4 2xl:space-x-6">
                    <a href="<?= BASE_URL ?>" class="<?= $current_page == 'index.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap">
                        Beranda
                    </a>
                    
                    <!-- Dropdown Kisah Kami -->
                    <div class="relative group">
                        <a href="#" class="text-white hover:text-sunrise transition duration-300 flex items-center whitespace-nowrap py-2">
                            Kisah Kami
                            <svg class="w-3.5 h-3.5 ml-1 text-white group-hover:text-sunrise" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <div class="absolute left-0 mt-0 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-gray-100 overflow-hidden">
                            <a href="<?= BASE_URL ?>kisah.php" class="block px-4 py-2.5 text-ink-muted hover:bg-cream hover:text-forest transition duration-200">Tentang Kami</a>
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>jalur.php" class="<?= $current_page == 'jalur.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap">
                        Telusur Jalur
                    </a>

                    <!-- Dropdown Siap Mendaki -->
                    <div class="relative group">
                        <a href="#" class="text-white hover:text-sunrise transition duration-300 flex items-center whitespace-nowrap py-2">
                            Siap Mendaki
                            <svg class="w-3.5 h-3.5 ml-1 text-white group-hover:text-sunrise" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <div class="absolute left-0 mt-0 w-56 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-gray-100 overflow-hidden">
                            <a href="<?= BASE_URL ?>persiapan.php" class="block px-4 py-2.5 text-ink-muted hover:bg-cream hover:text-forest transition duration-200 border-b border-gray-50">Persiapan & Logistik</a>
                            <a href="<?= BASE_URL ?>peraturan.php" class="block px-4 py-2.5 text-ink-muted hover:bg-cream hover:text-forest transition duration-200">Peraturan & Larangan</a>
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>alam.php" class="<?= $current_page == 'alam.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?> transition duration-300 whitespace-nowrap">
                        Alam Bismo
                    </a>

                    <a href="<?= BASE_URL ?>galeri.php" class="<?= $current_page == 'galeri.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?> transition duration-300 whitespace-nowrap">
                        Jejak Visual
                    </a>

                    <a href="<?= BASE_URL ?>berita.php" class="<?= $current_page == 'berita.php' || $current_page == 'berita-detail.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?> transition duration-300 whitespace-nowrap">
                        Kabar Bismo
                    </a>

                    <a href="<?= BASE_URL ?>kontak.php" class="<?= $current_page == 'kontak.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?> transition duration-300 whitespace-nowrap">
                        Temui Kami
                    </a>
                </div>
                
                <!-- Tombol WA -->
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-sunrise hover:bg-sunrise-dark text-white px-5 py-2 rounded-full transition duration-300 font-medium whitespace-nowrap shadow-sm text-xs 2xl:text-sm ml-auto">
                    Hubungi via WA
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="xl:hidden text-white focus:outline-none ml-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden xl:hidden pb-4 bg-forest-light">
            <a href="<?= BASE_URL ?>" class="block py-2 <?= $current_page == 'index.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Beranda</a>
            <a href="<?= BASE_URL ?>kisah.php" class="block py-2 <?= $current_page == 'kisah.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Kisah Kami</a>
            <a href="<?= BASE_URL ?>jalur.php" class="block py-2 <?= $current_page == 'jalur.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Telusur Jalur</a>
            <a href="<?= BASE_URL ?>persiapan.php" class="block py-2 <?= $current_page == 'persiapan.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Persiapan & Logistik</a>
            <a href="<?= BASE_URL ?>peraturan.php" class="block py-2 <?= $current_page == 'peraturan.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Peraturan & Larangan</a>
            <a href="<?= BASE_URL ?>alam.php" class="block py-2 <?= $current_page == 'alam.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Alam Bismo</a>
            <a href="<?= BASE_URL ?>galeri.php" class="block py-2 <?= $current_page == 'galeri.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Jejak Visual</a>
            <a href="<?= BASE_URL ?>berita.php" class="block py-2 <?= $current_page == 'berita.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Kabar Bismo</a>
            <a href="<?= BASE_URL ?>kontak.php" class="block py-2 <?= $current_page == 'kontak.php' ? 'text-[#E0BE45] font-bold' : 'text-white hover:text-[#E0BE45]' ?>">Temui Kami</a>
            <a href="https://wa.me/6281234567890" target="_blank" class="block py-2 text-[#E0BE45] font-bold">Hubungi via WA</a>
        </div>
    </div>
</nav>