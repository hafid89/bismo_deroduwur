<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="bg-transparent fixed w-full z-50 transition-colors duration-300" id="navbar">
    <div class="w-full px-4 lg:px-8">
        <div class="flex items-center h-20">
            
            <!-- Logo & Brand Name -->
            <div class="flex items-center shrink-0 mr-8 lg:mr-10">
                <a href="<?= BASE_URL ?>" class="flex items-center space-x-2.5 group">
                    <!-- LOGO BULAT -->
                    <div class="w-14 h-14 rounded-full overflow-hidden flex items-center justify-center shrink-0 border border-white/30 shadow-sm">
                        <img 
                            src="assets/images/logo.jpg" 
                            alt="Logo Gunung Bismo" 
                            class="w-full h-full object-cover scale-110"
                        >
                    </div>
                    
                    <!-- Text Brand -->
                    <div class="flex items-center space-x-1.5">
                        <div class="flex flex-col text-white uppercase font-bold leading-[0.85] text-left">
                            <span class="text-[12px] tracking-[0.24em]">GUNUNG</span>
                            <span class="text-[12px] tracking-[0.59em] mt-[3px]">BISMO</span>
                        </div>

                        <div class="h-7 w-[1.5px] bg-white/30 mx-0.5"></div>

                        <div class="flex flex-col text-[#E0BE45] leading-[0.9] text-left">
                            <span class="text-[12px] font-medium tracking-wide uppercase">Via</span>
                            <span class="text-[12px] font-semibold tracking-wider mt-[2px]">Deroduwur</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Navbar Desktop -->
            <div class="hidden xl:flex items-center space-x-6 2xl:space-x-8 text-lg flex-1 justify-between">
                <div class="flex items-center space-x-6 2xl:space-x-8">
                    <a href="<?= BASE_URL ?>" class="<?= $current_page == 'index.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap">
                        Beranda
                    </a>
                    
                    <a href="<?= BASE_URL ?>kisah.php" class="<?= $current_page == 'kisah.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap">
                        Kisah Kami
                    </a>

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
                        <div class="navbar-dropdown-menu absolute left-0 mt-0 w-56 bg-white/10 backdrop-blur-sm rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:bg-forest transition-all duration-300 z-50 border border-white/20 overflow-hidden">
                            <a href="<?= BASE_URL ?>persiapan.php" class="block px-4 py-2.5 text-white hover:bg-white/10 transition duration-200 border-b border-white/10">Persiapan & Logistik</a>
                            <a href="<?= BASE_URL ?>peraturan.php" class="block px-4 py-2.5 text-white hover:bg-white/10 transition duration-200">Peraturan & Larangan</a>
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

<script>
    // Navbar and dropdown scroll effect
    const navbar = document.getElementById('navbar');
    const dropdownMenus = document.querySelectorAll('.navbar-dropdown-menu');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 0) {
            navbar.classList.remove('bg-transparent');
            navbar.classList.add('bg-forest');
            
            // Update dropdown styling when scrolled
            dropdownMenus.forEach(menu => {
                menu.classList.remove('bg-white/10', 'backdrop-blur-sm', 'border-white/20');
                menu.classList.add('bg-forest', 'border-forest');
            });
        } else {
            navbar.classList.remove('bg-forest');
            navbar.classList.add('bg-transparent');
            
            // Restore original dropdown styling
            dropdownMenus.forEach(menu => {
                menu.classList.remove('bg-forest', 'border-forest');
                menu.classList.add('bg-white/10', 'backdrop-blur-sm', 'border-white/20');
            });
        }
    });
    
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    mobileMenuBtn.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
</script>