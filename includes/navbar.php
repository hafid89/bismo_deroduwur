<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="fixed w-full z-50 transition-all duration-300" id="navbar">
    <div class="w-full px-4 lg:px-8">
        <div class="flex items-center h-16 md:h-20">
            
            <!-- Logo & Brand Name -->
            <div class="flex items-center shrink-0 mr-4 md:mr-8 lg:mr-10">
                <a href="<?= BASE_URL ?>" class="flex items-center space-x-2 group">
                    <!-- LOGO BULAT -->
                    <div class="w-10 h-10 md:w-14 md:h-14 rounded-full overflow-hidden flex items-center justify-center shrink-0 border border-white/30 shadow-sm">
                        <img 
                            src="assets/images/logo.jpg" 
                            alt="Logo Gunung Bismo" 
                            class="w-full h-full object-cover scale-110"
                        >
                    </div>
                    
                    <!-- Text Brand - Lebih kecil di mobile -->
                    <div class="flex items-center space-x-1 md:space-x-1.5">
                        <div class="flex flex-col text-white uppercase font-bold leading-[0.85] text-left">
                            <span class="text-[10px] md:text-[12px] tracking-[0.2em] md:tracking-[0.24em]">GUNUNG</span>
                            <span class="text-[10px] md:text-[12px] tracking-[0.4em] md:tracking-[0.59em] mt-[2px] md:mt-[3px]">BISMO</span>
                        </div>

                        <div class="h-5 md:h-7 w-[1px] md:w-[1.5px] bg-white/30 mx-0.5"></div>

                        <div class="flex flex-col text-[#E0BE45] leading-[0.85] md:leading-[0.9] text-left">
                            <span class="text-[9px] md:text-[12px] font-medium tracking-wide uppercase">Via</span>
                            <span class="text-[9px] md:text-[12px] font-semibold tracking-wider mt-[1px] md:mt-[2px]">Deroduwur</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Navbar Desktop -->
            <div class="hidden xl:flex items-center space-x-6 2xl:space-x-8 text-lg flex-1 justify-between">
                <div class="flex items-center space-x-6 2xl:space-x-8">
                    <a href="<?= BASE_URL ?>" class="<?= $current_page == 'index.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap text-base 2xl:text-lg">
                        Beranda
                    </a>
                    
                    <a href="<?= BASE_URL ?>kisah.php" class="<?= $current_page == 'kisah.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap text-base 2xl:text-lg">
                        Kisah Kami
                    </a>

                    <a href="<?= BASE_URL ?>jalur.php" class="<?= $current_page == 'jalur.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap text-base 2xl:text-lg">
                        Telusur Jalur
                    </a>

                    <!-- Siap Mendaki -->
                    <a href="<?= BASE_URL ?>peraturan.php" class="<?= $current_page == 'peraturan.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap text-base 2xl:text-lg">
                        Siap Mendaki
                    </a>

                    <!-- Dropdown Alam Bismo -->
                    <div class="relative group">
                        <a href="<?= BASE_URL ?>alam.php" class="<?= $current_page == 'alam.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 flex items-center whitespace-nowrap py-2 text-base 2xl:text-lg">
                            Alam Bismo
                            <svg class="w-3 h-3 2xl:w-3.5 2xl:h-3.5 ml-1 text-white group-hover:text-sunrise transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <div class="navbar-dropdown-menu absolute left-0 mt-0 w-48 bg-white/10 backdrop-blur-sm rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-white/20 overflow-hidden">
                            <a href="<?= BASE_URL ?>alam.php#flora" class="block px-4 py-2.5 text-white hover:bg-white/10 transition duration-200 border-b border-white/10">Flora</a>
                            <a href="<?= BASE_URL ?>alam.php#fauna" class="block px-4 py-2.5 text-white hover:bg-white/10 transition duration-200">Fauna</a>
                        </div>
                    </div>

                    <!-- Dropdown Jejak Visual -->
                    <div class="relative group">
                        <a href="<?= BASE_URL ?>galeri.php" class="<?= $current_page == 'galeri.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 flex items-center whitespace-nowrap py-2 text-base 2xl:text-lg">
                            Jejak Visual
                            <svg class="w-3 h-3 2xl:w-3.5 2xl:h-3.5 ml-1 text-white group-hover:text-sunrise transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <div class="navbar-dropdown-menu absolute left-0 mt-0 w-48 bg-white/10 backdrop-blur-md rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-white/20 overflow-hidden">
                            <a href="<?= BASE_URL ?>galeri.php" class="block px-4 py-2.5 text-white hover:bg-white/10 transition duration-200 border-b border-white/10 	text-sm md:text-base">Semua</a>
                            <a href="<?= BASE_URL ?>galeri.php?filter=jalur" class="block px-4 py-2.5 text-white hover:bg-white/10 transition duration-200 border-b border-white/10 	text-sm md:text-base">Jalur</a>
                            <a href="<?= BASE_URL ?>galeri.php?filter=ekosistem" class="block px-4 py-2.5 text-white hover:bg-white/10 transition duration-200 border-b border-white/10 	text-sm md:text-base">Ekosistem</a>
                            <a href="<?= BASE_URL ?>galeri.php?filter=kegiatan" class="block px-4 py-2.5 text-white hover:bg-white/10 transition duration-200 	text-sm md:text-base">Kegiatan</a>
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>berita.php" class="<?= $current_page == 'berita.php' || $current_page == 'berita-detail.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap text-base 2xl:text-lg">
                        Kabar Bismo
                    </a>

                    <a href="<?= BASE_URL ?>kontak.php" class="<?= $current_page == 'kontak.php' ? 'text-sunrise font-bold' : 'text-white hover:text-sunrise' ?> transition duration-300 whitespace-nowrap text-base 2xl:text-lg">
                        Temui Kami
                    </a>
                </div>
                
                <!-- Tombol WA -->
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-sunrise hover:bg-sunrise-dark text-white px-4 py-1.5 2xl:px-5 2xl:py-2 rounded-full transition duration-300 font-medium whitespace-nowrap shadow-sm text-xs 2xl:text-sm ml-auto">
                    Hubungi via WA
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="xl:hidden text-white focus:outline-none ml-auto p-2 hover:bg-white/10 rounded-lg transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu - Dengan backdrop blur dan transparan -->
        <div id="mobileMenu" class="hidden xl:hidden pb-4 max-h-[80vh] overflow-y-auto">
            <!-- Mobile menu items dengan background transparan dan blur -->
            <div class="space-y-1">
                <a href="<?= BASE_URL ?>" class="block py-2.5 px-3 rounded-lg <?= $current_page == 'index.php' ? 'text-[#E0BE45] font-bold bg-white/10' : 'text-white hover:bg-white/10' ?> transition duration-200 text-sm">
                    Beranda
                </a>
                <a href="<?= BASE_URL ?>kisah.php" class="block py-2.5 px-3 rounded-lg <?= $current_page == 'kisah.php' ? 'text-[#E0BE45] font-bold bg-white/10' : 'text-white hover:bg-white/10' ?> transition duration-200 text-sm">
                    Kisah Kami
                </a>
                <a href="<?= BASE_URL ?>jalur.php" class="block py-2.5 px-3 rounded-lg <?= $current_page == 'jalur.php' ? 'text-[#E0BE45] font-bold bg-white/10' : 'text-white hover:bg-white/10' ?> transition duration-200 text-sm">
                    Telusur Jalur
                </a>
                <a href="<?= BASE_URL ?>peraturan.php" class="block py-2.5 px-3 rounded-lg <?= $current_page == 'peraturan.php' ? 'text-[#E0BE45] font-bold bg-white/10' : 'text-white hover:bg-white/10' ?> transition duration-200 text-sm">
                    Siap Mendaki
                </a>
                
                <!-- Mobile Submenu Alam Bismo -->
                <div class="pl-4 border-l-2 border-white/20 ml-3 mt-1">
                    <p class="text-[10px] text-white/50 uppercase tracking-wider mb-1.5 px-3">Alam Bismo</p>
                    <a href="<?= BASE_URL ?>alam.php" class="block py-2 px-3 rounded-lg <?= $current_page == 'alam.php' ? 'text-[#E0BE45] font-bold bg-white/10' : 'text-white/80 hover:bg-white/10' ?> transition duration-200 text-sm">
                        Semua
                    </a>
                    <a href="<?= BASE_URL ?>alam.php#flora" class="block py-2 px-3 rounded-lg text-white/80 hover:bg-white/10 transition duration-200 text-sm">
                        Flora
                    </a>
                    <a href="<?= BASE_URL ?>alam.php#fauna" class="block py-2 px-3 rounded-lg text-white/80 hover:bg-white/10 transition duration-200 text-sm">
                        Fauna
                    </a>
                </div>
                
                <!-- Mobile Submenu Jejak Visual -->
                <div class="pl-4 border-l-2 border-white/20 ml-3 mt-2">
                    <p class="text-[10px] text-white/50 uppercase tracking-wider mb-1.5 px-3">Jejak Visual</p>
                    <a href="<?= BASE_URL ?>galeri.php" class="block py-2 px-3 rounded-lg <?= $current_page == 'galeri.php' ? 'text-[#E0BE45] font-bold bg-white/10' : 'text-white/80 hover:bg-white/10' ?> transition duration-200 text-sm">
                        Semua
                    </a>
                    <a href="<?= BASE_URL ?>galeri.php?filter=jalur" class="block py-2 px-3 rounded-lg text-white/80 hover:bg-white/10 transition duration-200 text-sm">
                        Jalur
                    </a>
                    <a href="<?= BASE_URL ?>galeri.php?filter=ekosistem" class="block py-2 px-3 rounded-lg text-white/80 hover:bg-white/10 transition duration-200 text-sm">
                        Ekosistem
                    </a>
                    <a href="<?= BASE_URL ?>galeri.php?filter=kegiatan" class="block py-2 px-3 rounded-lg text-white/80 hover:bg-white/10 transition duration-200 text-sm">
                        Kegiatan
                    </a>
                </div>
                
                <a href="<?= BASE_URL ?>berita.php" class="block py-2.5 px-3 rounded-lg <?= $current_page == 'berita.php' ? 'text-[#E0BE45] font-bold bg-white/10' : 'text-white hover:bg-white/10' ?> transition duration-200 text-sm">
                    Kabar Bismo
                </a>
                <a href="<?= BASE_URL ?>kontak.php" class="block py-2.5 px-3 rounded-lg <?= $current_page == 'kontak.php' ? 'text-[#E0BE45] font-bold bg-white/10' : 'text-white hover:bg-white/10' ?> transition duration-200 text-sm">
                    Temui Kami
                </a>
                
                <!-- Tombol WA di mobile -->
                <a href="https://wa.me/6281234567890" target="_blank" class="block py-3 px-3 rounded-lg bg-[#E0BE45] hover:bg-[#C46F2A] text-white font-bold text-center transition duration-300 text-sm mt-3">
                    Hubungi via WA
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Navbar scroll effect - dengan backdrop blur untuk mobile
    const navbar = document.getElementById('navbar');
    const dropdownMenus = document.querySelectorAll('.navbar-dropdown-menu');
    const mobileMenu = document.getElementById('mobileMenu');
    
    function updateNavbarStyle() {
        const isScrolled = window.scrollY > 0;
        
        if (isScrolled) {
            // Saat scroll - solid dengan blur
            navbar.classList.remove('bg-transparent');
            navbar.classList.add('bg-forest');
            
            // Update dropdown styling
 dropdownMenus.forEach(menu => {
                menu.classList.remove('bg-white/10', 'backdrop-blur-sm', 'border-white/20');
                menu.classList.add('bg-forest-light');
            });
            
            // Mobile menu saat scroll - lebih solid
            if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.style.background = '#418B24';
                mobileMenu.style.backdropFilter = 'blur(8px)';
            }
        } else {
            // Saat di atas - transparan dengan blur
            navbar.classList.remove('bg-forest');
            navbar.classList.add('bg-transparent');
            
            // Restore dropdown styling
            dropdownMenus.forEach(menu => {
                menu.classList.remove('bg-forest', 'border-forest');
            });
            
            // Mobile menu saat di atas - transparan dengan blur
            if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.style.background = 'rgba(0, 0, 0, 0.3)';
                mobileMenu.style.backdropFilter = 'blur(12px)';
            }
        }
    }
    
    // Set initial state
    updateNavbarStyle();
    
    // Listen to scroll
    window.addEventListener('scroll', updateNavbarStyle);
    
    // Mobile menu toggle dengan background blur
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    
    mobileMenuBtn.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
        
        // Set background style untuk mobile menu
        if (!mobileMenu.classList.contains('hidden')) {
            const isScrolled = window.scrollY > 0;
            if (isScrolled) {
                mobileMenu.style.background = 'rgba(47, 82, 51, 0.98)';
                mobileMenu.style.backdropFilter = 'blur(8px)';
            } else {
                mobileMenu.style.background = 'rgba(0, 0, 0, 0.3)';
                mobileMenu.style.backdropFilter = 'blur(12px)';
            }
        }
    });
    
    // Close mobile menu on window resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1280) { // xl breakpoint
            mobileMenu.classList.add('hidden');
        }
    });
</script>

<style>
    /* Tambahan styling untuk mobile menu */
    @media (max-width: 1279px) {
        #mobileMenu {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 0 0 16px 16px;
            margin: 0 -4px;
            padding: 8px 12px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Smooth transition untuk mobile menu */
        #mobileMenu {
            transition: all 0.3s ease;
        }
    }
    
    /* Saat navbar scroll, dropdown menyesuaikan */
    .bg-forest .navbar-dropdown-menu {
        background: rgba(47, 82, 51, 0.95);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
</style>