document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Inisialisasi Swiper Carousel Hero Section
    if (document.querySelector('.hero-swiper')) {
        const heroSwiper = new Swiper('.hero-swiper', {
            loop: true,
            speed: 800,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            }
        });

        // Toggle autoplay on progress click
        const progressEl = document.querySelector('.autoplay-progress');
        if (progressEl) {
            progressEl.addEventListener('click', function() {
                if (heroSwiper.autoplay.running) {
                    heroSwiper.autoplay.stop();
                    this.querySelector('span').textContent = '▶';
                } else {
                    heroSwiper.autoplay.start();
                    this.querySelector('span').textContent = '⏸';
                }
            });
        }
    }

    // 2. Navbar and mobile menu
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const navbar = document.querySelector('nav');
    const solidNavbar = navbar && navbar.dataset.solid === 'true';
    const dropdownMenus = document.querySelectorAll('.navbar-dropdown-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');

            if (!mobileMenu.classList.contains('hidden')) {
                const isScrolled = window.scrollY > 0;
                mobileMenu.style.background = isScrolled
                    ? 'rgba(47, 82, 51, 0.98)'
                    : 'rgba(0, 0, 0, 0.3)';
                mobileMenu.style.backdropFilter = isScrolled
                    ? 'blur(8px)'
                    : 'blur(12px)';
            }
        });
    }

    // 3. Smooth Scroll untuk Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                e.preventDefault();
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

   // 4. Navbar Scroll Effect - default transparan, jadi solid saat discroll ke bawah
    if (navbar) {
        const toggleBg = () => {
            if (solidNavbar) {
                navbar.classList.remove('bg-transparent', 'bg-forest-light');
                navbar.classList.add('bg-forest', 'shadow-lg');
                return;
            }

            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > 10) {
                // Jika sudah discroll sedikit, tampilkan background solid
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-forest-light', 'shadow-lg');
                dropdownMenus.forEach(menu => {
                    menu.classList.remove('bg-white/10', 'backdrop-blur-sm', 'border-white/20');
                    menu.classList.add('bg-forest-light');
                });

                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.style.background = '#418B24';
                    mobileMenu.style.backdropFilter = 'blur(8px)';
                }
            } else {
                // Kembali ke kondisi transparan saat di paling atas
                navbar.classList.add('bg-transparent');
                navbar.classList.remove('bg-forest', 'bg-forest-light', 'shadow-lg', 'shadow-xl');
                dropdownMenus.forEach(menu => {
                    menu.classList.remove('bg-forest', 'bg-forest-light', 'border-forest');
                });

                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.style.background = 'rgba(0, 0, 0, 0.3)';
                    mobileMenu.style.backdropFilter = 'blur(12px)';
                }
            }
        };

        // Cek saat load dan setiap scroll
        toggleBg();
        window.addEventListener('scroll', toggleBg, { passive: true });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1280) {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // 5. ScrollReveal for reveal-bottom cards
    if (typeof ScrollReveal !== 'undefined') {
        window.sr = ScrollReveal({
            duration: 1350,
            distance: '250px',
            easing: 'ease-out',
        });

        sr.reveal('.reveal-bottom', {
            origin: 'bottom',
            reset: false,
            interval: 200,
        });
    }
});
