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

    // 2. Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
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
    const navbar = document.querySelector('nav');
    if (navbar) {
        const toggleBg = () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > 10) {
                // Jika sudah discroll sedikit, tampilkan background solid
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-forest-light', 'shadow-lg');
            } else {
                // Kembali ke kondisi transparan saat di paling atas
                navbar.classList.add('bg-transparent');
                navbar.classList.remove('bg-forest-light', 'shadow-lg', 'shadow-xl');
            }
        };

        // Cek saat load dan setiap scroll
        toggleBg();
        window.addEventListener('scroll', toggleBg, { passive: true });
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
