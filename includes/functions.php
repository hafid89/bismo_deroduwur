<?php
require_once __DIR__ . '/config.php';

// Fungsi untuk membuat slug
function createSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Fungsi untuk upload file
function uploadFile($file, $targetDir, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp']) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error uploading file'];
    }

    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, ['heic', 'heif'], true)) {
        return ['success' => false, 'message' => 'Format HEIC/HEIF belum didukung. Konversi foto ke JPG, PNG, atau WEBP lalu unggah kembali.'];
    }

    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }

    if ($fileSize > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (maks 5MB)'];
    }

    $newFileName = uniqid() . '.' . $fileExt;
    $targetPath = $targetDir . $newFileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($fileTmp, $targetPath)) {
        return ['success' => true, 'filename' => $newFileName];
    }

    return ['success' => false, 'message' => 'Gagal upload file'];
}

// Fungsi untuk format tanggal Indonesia
function formatTanggal($date) {
    $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $timestamp = strtotime($date);
    return date('d', $timestamp) . ' ' . $months[date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp);
}

// Fungsi untuk truncate text
function truncateText($text, $length = 150) {
    $text = strip_tags($text);
    if (strlen($text) <= $length) {
        return $text;
    }
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    return $text . '...';
}

// Fungsi untuk cek login
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

// Fungsi untuk redirect
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

// ============ FUNGSI BERITA ============
// Fungsi untuk get berita terbaru
function getBeritaTerbaru($limit = 3) {
    global $pdo;
    $limit = intval($limit);
    $stmt = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT $limit");
    return $stmt->fetchAll();
}

// Fungsi untuk get total berita
function getTotalBerita() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM berita");
    $result = $stmt->fetch();
    return $result ? (int)$result['total'] : 0;
}

// Fungsi untuk get berita by id
function getBeritaById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Fungsi untuk get related berita
function getRelatedBerita($id, $limit = 3) {
    global $pdo;
    $limit = intval($limit);
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id != ? ORDER BY tanggal DESC LIMIT $limit");
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

// Fungsi untuk get berita dengan pagination
function getBeritaPaginated($page = 1, $limit = 10) {
    global $pdo;
    $page = intval($page);
    $limit = intval($limit);
    $offset = ($page - 1) * $limit;
    $stmt = $pdo->query("SELECT * FROM berita ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
    return $stmt->fetchAll();
}

// ============ FUNGSI GALERI ============
// Fungsi untuk get galeri
function getGaleri($limit = null) {
    global $pdo;
    $sql = "SELECT * FROM galeri ORDER BY created_at DESC";
    if ($limit) {
        $limit = intval($limit);
        $sql .= " LIMIT $limit";
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Fungsi untuk get total galeri
function getTotalGaleri() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM galeri");
    $result = $stmt->fetch();
    return $result ? (int)$result['total'] : 0;
}

// Fungsi untuk get galeri by tag
function getGaleriByTag($tag, $limit = null) {
    global $pdo;
    $sql = "SELECT * FROM galeri WHERE tag = ? ORDER BY created_at DESC";
    if ($limit) {
        $limit = intval($limit);
        $sql .= " LIMIT $limit";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tag]);
    return $stmt->fetchAll();
}

// Fungsi untuk get galeri by kategori
function getGaleriByKategori($kategori, $limit = null) {
    global $pdo;
    $sql = "SELECT * FROM galeri WHERE kategori = ? ORDER BY created_at DESC";
    if ($limit) {
        $limit = intval($limit);
        $sql .= " LIMIT $limit";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$kategori]);
    return $stmt->fetchAll();
}

// Fungsi untuk get galeri dengan pagination
function getGaleriPaginated($page = 1, $limit = 12) {
    global $pdo;
    $page = intval($page);
    $limit = intval($limit);
    $offset = ($page - 1) * $limit;
    $stmt = $pdo->query("SELECT * FROM galeri ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
    return $stmt->fetchAll();
}

// Ubah $_FILES (input multiple) jadi daftar per file, slot kosong diabaikan
function normalizeUploadedFiles($input) {
    $result = [];
    if (!$input || !isset($input['name'])) return $result;
    if (!is_array($input['name'])) {
        if ($input['error'] !== UPLOAD_ERR_NO_FILE) $result[] = $input;
        return $result;
    }
    foreach ($input['name'] as $i => $name) {
        if ($input['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
        $result[] = [
            'name' => $name, 'type' => $input['type'][$i], 'tmp_name' => $input['tmp_name'][$i],
            'error' => $input['error'][$i], 'size' => $input['size'][$i],
        ];
    }
    return $result;
}

// Validasi satu foto. Mengembalikan pesan error, atau '' kalau valid
function validateImageFile($file) {
    $name = htmlspecialchars($file['name']);
    if ($file['error'] !== UPLOAD_ERR_OK) return "$name: gagal diupload (kode {$file['error']})";
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['heic', 'heif'], true)) {
        return "$name: format HEIC/HEIF belum didukung. Konversi ke JPG, PNG, atau WEBP.";
    }
    if ($file['size'] > 5 * 1024 * 1024) return "$name: ukuran maksimal 5MB";
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true)) {
        return "$name: tipe file tidak diizinkan (gunakan JPG, PNG, GIF, atau WEBP)";
    }
    return '';
}

// ============ FUNGSI FLORA ============
function getFlora() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM flora ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function getFloraById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM flora WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getTotalFlora($search = '') {
    global $pdo;
    if (!empty($search)) {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) as total FROM flora 
             WHERE nama LIKE ? OR nama_ilmiah LIKE ? OR deskripsi LIKE ? OR lokasi LIKE ?"
        );
        $term = "%$search%";
        $stmt->execute([$term, $term, $term, $term]);
    } else {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM flora");
    }
    $result = $stmt->fetch();
    return $result ? (int)$result['total'] : 0;
}

function getFloraPaginated($page = 1, $limit = 10, $search = '') {
    global $pdo;
    $page   = intval($page);
    $limit  = intval($limit);
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $sql = "SELECT * FROM flora 
                WHERE nama LIKE ? OR nama_ilmiah LIKE ? OR deskripsi LIKE ? OR lokasi LIKE ?
                ORDER BY created_at DESC 
                LIMIT $limit OFFSET $offset";
        $stmt = $pdo->prepare($sql);
        $term = "%$search%";
        $stmt->execute([$term, $term, $term, $term]);
    } else {
        $stmt = $pdo->query(
            "SELECT * FROM flora ORDER BY created_at DESC LIMIT $limit OFFSET $offset"
        );
    }
    return $stmt->fetchAll();
}

// ============ FUNGSI FAUNA ============
function getFauna() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM fauna ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function getFaunaById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM fauna WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getTotalFauna($search = '') {
    global $pdo;
    if (!empty($search)) {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) as total FROM fauna 
             WHERE nama LIKE ? OR nama_ilmiah LIKE ? OR deskripsi LIKE ? OR lokasi LIKE ?"
        );
        $term = "%$search%";
        $stmt->execute([$term, $term, $term, $term]);
    } else {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM fauna");
    }
    $result = $stmt->fetch();
    return $result ? (int)$result['total'] : 0;
}

function getFaunaPaginated($page = 1, $limit = 10, $search = '') {
    global $pdo;
    $page   = intval($page);
    $limit  = intval($limit);
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $sql = "SELECT * FROM fauna 
                WHERE nama LIKE ? OR nama_ilmiah LIKE ? OR deskripsi LIKE ? OR lokasi LIKE ?
                ORDER BY created_at DESC 
                LIMIT $limit OFFSET $offset";
        $stmt = $pdo->prepare($sql);
        $term = "%$search%";
        $stmt->execute([$term, $term, $term, $term]);
    } else {
        $stmt = $pdo->query(
            "SELECT * FROM fauna ORDER BY created_at DESC LIMIT $limit OFFSET $offset"
        );
    }
    return $stmt->fetchAll();
}

// ============ FUNGSI PERATURAN ============
function getPeraturan() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM peraturan ORDER BY kategori, id");
    return $stmt->fetchAll();
}

function getPeraturanById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM peraturan WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getPeraturanByKategori($kategori) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM peraturan WHERE kategori = ? ORDER BY id");
    $stmt->execute([$kategori]);
    return $stmt->fetchAll();
}

function getTotalPeraturan() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM peraturan");
    $result = $stmt->fetch();
    return $result ? (int)$result['total'] : 0;
}

function getPeraturanPaginated($page = 1, $limit = 10) {
    global $pdo;
    $page = intval($page);
    $limit = intval($limit);
    $offset = ($page - 1) * $limit;
    $stmt = $pdo->query("SELECT * FROM peraturan ORDER BY kategori, id LIMIT $limit OFFSET $offset");
    return $stmt->fetchAll();
}

// ============ FUNGSI UMUM ============
// Fungsi untuk pagination (generic)
function getPaginatedData($table, $page = 1, $limit = 10, $orderBy = 'created_at DESC') {
    global $pdo;
    $page = intval($page);
    $limit = intval($limit);
    $offset = ($page - 1) * $limit;
    
    $sql = "SELECT * FROM $table ORDER BY $orderBy LIMIT $limit OFFSET $offset";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Fungsi untuk get total data (generic)
function getTotalData($table) {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM $table");
    $result = $stmt->fetch();
    return $result ? (int)$result['total'] : 0;
}

// Fungsi untuk sanitasi input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function sanitizeRichText($html) {
    if (!class_exists('DOMDocument')) {
        return nl2br(htmlspecialchars(strip_tags($html), ENT_QUOTES, 'UTF-8'));
    }

    $rootId = 'article-root-' . bin2hex(random_bytes(8));
    $document = new DOMDocument('1.0', 'UTF-8');
    $previousErrors = libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="UTF-8"><div id="' . $rootId . '">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    libxml_use_internal_errors($previousErrors);

    $xpath = new DOMXPath($document);
    $root = $xpath->query('//*[@id="' . $rootId . '"]')->item(0);
    if (!$root) {
        return '';
    }

    $allowedTags = ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li', 'h2', 'h3', 'h4', 'blockquote', 'a', 'font'];
    $sanitizeChildren = function ($parent) use (&$sanitizeChildren, $allowedTags) {
        foreach (iterator_to_array($parent->childNodes) as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            $tag = strtolower($child->nodeName);
            if (in_array($tag, ['script', 'style', 'iframe', 'object', 'svg', 'math'], true)) {
                $parent->removeChild($child);
                continue;
            }

            if (!in_array($tag, $allowedTags, true)) {
                $sanitizeChildren($child);
                while ($child->firstChild) {
                    $parent->insertBefore($child->firstChild, $child);
                }
                $parent->removeChild($child);
                continue;
            }

            foreach (iterator_to_array($child->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                $value = trim($attribute->value);
                $keep = false;

                if ($tag === 'a' && $name === 'href') {
                    $value = preg_replace('/[\x00-\x20]+/', '', $value);
                    $scheme = parse_url($value, PHP_URL_SCHEME);
                    $keep = ($scheme === null && strpos($value, '//') !== 0)
                        || in_array(strtolower((string)$scheme), ['http', 'https', 'mailto'], true);
                } elseif ($tag === 'font' && $name === 'face') {
                    $keep = in_array(strtolower($value), ['arial', 'georgia', 'times new roman', 'verdana', 'trebuchet ms'], true);
                } elseif ($tag === 'font' && $name === 'size') {
                    $keep = in_array($value, ['1', '2', '3', '4', '5', '6', '7'], true);
                }

                if (!$keep) {
                    $child->removeAttribute($attribute->name);
                } elseif ($name === 'href') {
                    $child->setAttribute('href', $value);
                }
            }

            $sanitizeChildren($child);
        }
    };
    $sanitizeChildren($root);

    $cleanHtml = '';
    foreach ($root->childNodes as $child) {
        $cleanHtml .= $document->saveHTML($child);
    }
    return $cleanHtml;
}

// ============ FUNGSI KOMENTAR (RICH TEXT) ============

/**
 * Sanitasi HTML komentar user.
 * Hanya izinkan tag & atribut tertentu untuk mencegah XSS.
 * Output HTML aman untuk langsung di-echo.
 */
function sanitizeCommentHtml(string $html): string {
    $html = trim($html);
    if ($html === '') return '';

    // Fallback kalau DOMDocument tidak tersedia
    if (!class_exists('DOMDocument')) {
        $allowed = '<p><br><strong><b><em><i><u><s><strike><del><sup><sub>'
                 . '<a><ul><ol><li><blockquote><code><pre><h1><h2><h3><h4>'
                 . '<table><thead><tbody><tr><th><td><span><div>';
        return strip_tags($html, $allowed);
    }

    $rootId = 'comment-root-' . bin2hex(random_bytes(8));
    $document = new DOMDocument('1.0', 'UTF-8');
    $previousErrors = libxml_use_internal_errors(true);
    $document->loadHTML(
        '<?xml encoding="UTF-8"><div id="' . $rootId . '">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();
    libxml_use_internal_errors($previousErrors);

    $xpath = new DOMXPath($document);
    $root = $xpath->query('//*[@id="' . $rootId . '"]')->item(0);
    if (!$root) return '';

    // Tag yang diizinkan (lebih luas dari artikel, cocok dengan toolbar Quill)
    $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'del',
        'sup', 'sub', 'a', 'ul', 'ol', 'li', 'blockquote',
        'code', 'pre', 'h1', 'h2', 'h3', 'h4',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'span', 'div'
    ];

    // Atribut yang diizinkan per tag
    $allowedAttrs = [
        'a'    => ['href'],
        'span' => ['class'],
        'div'  => ['class'],
        'code' => ['class'],
        'pre'  => ['class'],
    ];

    $sanitizeChildren = function ($parent) use (&$sanitizeChildren, $allowedTags, $allowedAttrs) {
        foreach (iterator_to_array($parent->childNodes) as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) continue;

            $tag = strtolower($child->nodeName);

            // Blokir total tag berbahaya
            if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'svg', 'math', 'form', 'input', 'button'], true)) {
                $parent->removeChild($child);
                continue;
            }

            // Tag tidak diizinkan → hapus tag, angkat anaknya ke parent
            if (!in_array($tag, $allowedTags, true)) {
                $sanitizeChildren($child);
                while ($child->firstChild) {
                    $parent->insertBefore($child->firstChild, $child);
                }
                $parent->removeChild($child);
                continue;
            }

            // Filter atribut
            $attrsAllowed = $allowedAttrs[$tag] ?? [];
            foreach (iterator_to_array($child->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                $value = trim($attribute->value);
                $keep = in_array($name, $attrsAllowed, true);

                // Validasi khusus href
                if ($tag === 'a' && $name === 'href' && $keep) {
                    $value = preg_replace('/[\x00-\x20]+/', '', $value);
                    $scheme = parse_url($value, PHP_URL_SCHEME);
                    $validScheme = ($scheme === null && strpos($value, '//') !== 0)
                        || in_array(strtolower((string)$scheme), ['http', 'https', 'mailto'], true);
                    if (!$validScheme) {
                        $keep = false;
                    } else {
                        $child->setAttribute('href', $value);
                        $child->setAttribute('rel', 'nofollow noopener');
                        $child->setAttribute('target', '_blank');
                    }
                }

                if (!$keep) {
                    $child->removeAttribute($attribute->name);
                }
            }

            $sanitizeChildren($child);
        }
    };
    $sanitizeChildren($root);

    $cleanHtml = '';
    foreach ($root->childNodes as $child) {
        $cleanHtml .= $document->saveHTML($child);
    }
    return $cleanHtml;
}

/**
 * Konversi HTML jadi plain text.
 * Dipakai untuk: hitung panjang karakter, preview, SEO, dll.
 */
function htmlToPlainText(string $html): string {
    $text = preg_replace('/<br\s*\/?>/i', "\n", $html);
    $text = preg_replace('/<\/(p|div|h[1-6]|li|blockquote)>/i', "\n", $text);
    $text = strip_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\n{3,}/', "\n\n", $text);
    return trim($text);
}