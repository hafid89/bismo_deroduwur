// Simple Lightbox
document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.createElement('div');
    lightbox.id = 'lightbox';
    
    const lightboxImg = document.createElement('img');
    lightbox.appendChild(lightboxImg);

    const closeBtn = document.createElement('button');
    closeBtn.className = 'close-btn';
    closeBtn.innerHTML = '✕';
    lightbox.appendChild(closeBtn);

    document.body.appendChild(lightbox);

    // Open lightbox
    document.querySelectorAll('[data-lightbox]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const imgSrc = this.getAttribute('href');
            lightboxImg.src = imgSrc;
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });

    // Close lightbox
    lightbox.addEventListener('click', function(e) {
        if (e.target === this || e.target === closeBtn) {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Close with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && lightbox.style.display === 'flex') {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});