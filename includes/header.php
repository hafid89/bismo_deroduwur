<?php
// Pastikan BASE_URL sudah didefinisikan
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/config.php';
}

// Set default title jika belum ada
$page_title = $page_title ?? 'Gunung Bismo via Deroduwur';
$page_description = $page_description ?? 'Basecamp pendakian Gunung Bismo via Deroduwur. Jalur asri, belum banyak terjamah.';
$page_keywords = $page_keywords ?? 'Gunung Bismo, Deroduwur, Pendakian, Basecamp, Wonosobo, Wisata Alam';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ===== FAVICON / LOGO DI TAB ===== -->
    <!-- Favicon untuk semua browser -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/images/favicon-16x16.png">
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/images/favicon.ico">
    
    <!-- Apple Touch Icon (untuk iOS) -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/images/apple-touch-icon.png">
    
    <!-- Android Chrome -->
    <link rel="manifest" href="<?= BASE_URL ?>assets/images/site.webmanifest">
    <meta name="theme-color" content="#2F5233">
    
    <!-- ===== META TAGS ===== -->
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
    <meta name="author" content="Kelompok KKN 84.384 UPNVYK">
    <meta name="robots" content="index, follow">
    
    <!-- ===== OPEN GRAPH (Social Media) ===== -->
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
    <meta property="og:image" content="<?= BASE_URL ?>assets/images/og-image.jpg">
    <meta property="og:url" content="<?= BASE_URL . basename($_SERVER['PHP_SELF']) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Gunung Bismo via Deroduwur">
    
    <!-- ===== TWITTER CARD ===== -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
    <meta name="twitter:image" content="<?= BASE_URL ?>assets/images/og-image.jpg">
    
    <!-- ===== TITLE ===== -->
    <title><?= htmlspecialchars($page_title) ?></title>
    
    <!-- ===== TAILWIND CSS ===== -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: '#2F5233',
                        'forest-light': '#4A7A4E',
                        sunrise: '#E0BE45',
                        'sunrise-dark': '#C46F2A',
                        earth: '#A9784B',
                        cream: '#FAF7F2',
                        ink: '#26261F',
                        'ink-muted': '#5C5C50',
                        white: '#FFFFFF',
                    }
                }
            }
        }
    </script>
    
    <!-- ===== FONT ===== -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- ===== CUSTOM CSS ===== -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    
    <!-- ===== BOOTSTRAP ICONS ===== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* Global Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Inter', 'Outfit', sans-serif;
            background: #FAF7F2;
            color: #26261F;
            overflow-x: hidden;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f0ebe6;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #2F5233;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4A7A4E;
        }
        
        /* Selection */
        ::selection {
            background: #2F5233;
            color: #ffffff;
        }
        
        /* Animasi Fade In */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }
        .fade-in-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .fade-in-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .fade-in-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .fade-in-delay-4 { animation-delay: 0.4s; opacity: 0; }
        .fade-in-delay-5 { animation-delay: 0.5s; opacity: 0; }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }
        
        @media (min-width: 640px) {
            .container { padding: 0 24px; }
        }
        @media (min-width: 1024px) {
            .container { padding: 0 32px; }
        }
    </style>
</head>
<body>