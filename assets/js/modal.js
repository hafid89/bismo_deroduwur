// Modal Popup untuk Galeri
document.addEventListener('DOMContentLoaded', function() {
    // Buat elemen modal
    const modal = document.createElement('div');
    modal.id = 'imageModal';
    modal.style.cssText = `
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 99999;
        justify-content: center;
        align-items: center;
        padding: 20px;
        backdrop-filter: blur(5px);
    `;

    // Container modal
    const modalContent = document.createElement('div');
    modalContent.style.cssText = `
        background: white;
        border-radius: 16px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        animation: modalFadeIn 0.3s ease;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    `;

    // Tombol Close
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '✕';
    closeBtn.style.cssText = `
        position: sticky;
        top: 10px;
        float: right;
        margin: 10px 10px 0 0;
        background: #2F5233;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    closeBtn.onmouseover = () => {
        closeBtn.style.transform = 'scale(1.1)';
        closeBtn.style.background = '#C46F2A';
    };
    closeBtn.onmouseout = () => {
        closeBtn.style.transform = 'scale(1)';
        closeBtn.style.background = '#2F5233';
    };

    // Gambar
    const modalImg = document.createElement('img');
    modalImg.style.cssText = `
        width: 100%;
        height: 350px;
        object-fit: cover;
        display: block;
    `;

    // Container info
    const infoContainer = document.createElement('div');
    infoContainer.style.cssText = `
        padding: 20px;
    `;

    // Judul
    const titleEl = document.createElement('h3');
    titleEl.style.cssText = `
        font-size: 20px;
        font-weight: 700;
        color: #2F5233;
        margin-bottom: 8px;
    `;

    // Kategori
    const categoryEl = document.createElement('p');
    categoryEl.style.cssText = `
        font-size: 14px;
        color: #A9784B;
        margin-bottom: 8px;
    `;

    // Deskripsi
    const descEl = document.createElement('p');
    descEl.style.cssText = `
        font-size: 14px;
        color: #5C5C50;
        line-height: 1.6;
        margin-bottom: 12px;
    `;

    // Tanggal
    const dateEl = document.createElement('p');
    dateEl.style.cssText = `
        font-size: 12px;
        color: #999;
        border-top: 1px solid #eee;
        padding-top: 12px;
        margin-top: 8px;
    `;

    // Susun elemen
    infoContainer.appendChild(titleEl);
    infoContainer.appendChild(categoryEl);
    infoContainer.appendChild(descEl);
    infoContainer.appendChild(dateEl);

    modalContent.appendChild(closeBtn);
    modalContent.appendChild(modalImg);
    modalContent.appendChild(infoContainer);
    modal.appendChild(modalContent);
    document.body.appendChild(modal);

    // Tambahkan CSS Animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        @keyframes modalFadeOut {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            to {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
        }
        #imageModal::-webkit-scrollbar {
            width: 6px;
        }
        #imageModal::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        #imageModal::-webkit-scrollbar-thumb {
            background: #2F5233;
            border-radius: 3px;
        }
        #imageModal::-webkit-scrollbar-thumb:hover {
            background: #4A7A4E;
        }
    `;
    document.head.appendChild(style);

    // Fungsi untuk membuka modal
    window.openModal = function(imageSrc, title, category, description, date) {
        modalImg.src = imageSrc;
        modalImg.alt = title || 'Gambar';
        titleEl.textContent = title || 'Tanpa Judul';
        categoryEl.textContent = category ? '📁 ' + category : '';
        descEl.textContent = description || '';
        dateEl.textContent = date ? '📅 ' + date : '';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        modalContent.style.animation = 'modalFadeIn 0.3s ease';
    };

    // Fungsi untuk menutup modal
    window.closeModal = function() {
        modalContent.style.animation = 'modalFadeOut 0.2s ease';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 200);
    };

    // Event close
    closeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        window.closeModal();
    });

    // Klik di luar modal untuk tutup
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            window.closeModal();
        }
    });

    // ESC key untuk tutup
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            window.closeModal();
        }
    });
});