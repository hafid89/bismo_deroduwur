// Lightbox dengan Gallery Navigation
document.addEventListener('DOMContentLoaded', function() {
    let currentIndex = 0;
    let galleryImages = [];
    let lightbox = document.getElementById('lightbox');
    
    function createLightbox() {
        if (document.getElementById('lightbox')) return;
        
        lightbox = document.createElement('div');
        lightbox.id = 'lightbox';
        lightbox.style.cssText = `
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 99999;
            justify-content: center;
            align-items: center;
            padding: 20px;
        `;

        // Container untuk gambar
        const imgContainer = document.createElement('div');
        imgContainer.style.cssText = `
            position: relative;
            max-width: 90%;
            max-height: 90%;
        `;
        
        const lightboxImg = document.createElement('img');
        lightboxImg.id = 'lightbox-img';
        lightboxImg.style.cssText = `
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            display: block;
            margin: 0 auto;
        `;
        imgContainer.appendChild(lightboxImg);

        // Tombol close
        const closeBtn = document.createElement('button');
        closeBtn.id = 'lightbox-close';
        closeBtn.innerHTML = '✕';
        closeBtn.style.cssText = `
            position: fixed;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 100000;
            transition: transform 0.3s ease;
            font-family: Arial, sans-serif;
        `;
        closeBtn.onclick = function(e) {
            e.stopPropagation();
            closeLightbox();
        };

        // Tombol prev
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '‹';
        prevBtn.style.cssText = `
            position: fixed;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 50px;
            background: rgba(0,0,0,0.5);
            border: none;
            cursor: pointer;
            padding: 20px 15px;
            border-radius: 50%;
            z-index: 100000;
            transition: all 0.3s ease;
        `;
        prevBtn.onmouseover = () => prevBtn.style.background = 'rgba(47,82,51,0.8)';
        prevBtn.onmouseout = () => prevBtn.style.background = 'rgba(0,0,0,0.5)';
        prevBtn.onclick = function(e) {
            e.stopPropagation();
            navigateGallery(-1);
        };

        // Tombol next
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '›';
        nextBtn.style.cssText = `
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 50px;
            background: rgba(0,0,0,0.5);
            border: none;
            cursor: pointer;
            padding: 20px 15px;
            border-radius: 50%;
            z-index: 100000;
            transition: all 0.3s ease;
        `;
        nextBtn.onmouseover = () => nextBtn.style.background = 'rgba(47,82,51,0.8)';
        nextBtn.onmouseout = () => nextBtn.style.background = 'rgba(0,0,0,0.5)';
        nextBtn.onclick = function(e) {
            e.stopPropagation();
            navigateGallery(1);
        };

        // Counter
        const counter = document.createElement('div');
        counter.id = 'lightbox-counter';
        counter.style.cssText = `
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 14px;
            background: rgba(0,0,0,0.5);
            padding: 8px 16px;
            border-radius: 20px;
            z-index: 100000;
        `;

        lightbox.appendChild(imgContainer);
        lightbox.appendChild(closeBtn);
        lightbox.appendChild(prevBtn);
        lightbox.appendChild(nextBtn);
        lightbox.appendChild(counter);
        document.body.appendChild(lightbox);

        // Klik background
        lightbox.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });

        // ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
            if (e.key === 'ArrowLeft') {
                navigateGallery(-1);
            }
            if (e.key === 'ArrowRight') {
                navigateGallery(1);
            }
        });
    }

    function openLightbox(index) {
        if (!galleryImages.length) return;
        currentIndex = index;
        const lightboxImg = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        const lightbox = document.getElementById('lightbox');
        
        if (lightboxImg && lightbox) {
            lightboxImg.src = galleryImages[currentIndex];
            lightbox.style.display = 'flex';
            if (counter) {
                counter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;
            }
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        if (lightbox) {
            lightbox.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function navigateGallery(direction) {
        currentIndex = (currentIndex + direction + galleryImages.length) % galleryImages.length;
        const lightboxImg = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        if (lightboxImg) {
            lightboxImg.src = galleryImages[currentIndex];
        }
        if (counter) {
            counter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;
        }
    }

    // Inisialisasi
    createLightbox();

    // Kumpulkan semua gambar dari data-lightbox
    document.querySelectorAll('[data-lightbox]').forEach(link => {
        const href = link.getAttribute('href');
        if (href && !galleryImages.includes(href)) {
            galleryImages.push(href);
        }
    });

    // Event klik
    document.querySelectorAll('[data-lightbox]').forEach((link, index) => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const imgIndex = galleryImages.indexOf(href);
            openLightbox(imgIndex >= 0 ? imgIndex : 0);
        });
    });

    // Expose functions globally
    window.openLightbox = openLightbox;
    window.closeLightbox = closeLightbox;
    window.navigateGallery = navigateGallery;
});