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

   // 4. Navbar Scroll Effect - TETAP HIJAU SOLID
    const navbar = document.querySelector('nav');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 50) {
                // Saat discroll, cukup tambahkan bayangan (shadow) agar terpisah dari konten
                navbar.classList.add('shadow-xl');
            } else {
                // Saat kembali ke paling atas, kembalikan shadow standar
                navbar.classList.remove('shadow-xl');
            }
        });
    }
});