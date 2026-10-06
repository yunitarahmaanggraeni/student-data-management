<?php
// --- BACKEND LOGIC: GOOGLE SHEETS TETAP AMAN 100% ---
require_once __DIR__ . '/config/config.php';

$range = 'Sheet1!A:D'; 
$values = [];
$error_message = '';
$search_query = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

try {
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

$toast_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'edit_sukses') $toast_message = 'Yay! Data berhasil diupdate 💖';
    if ($_GET['status'] == 'delete_sukses') $toast_message = 'Wush! Data sudah dihapus 🚀';
}

$total_students = (!empty($values)) ? max(0, count($values) - 1) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universitas Pamulang - Cloud Candy UI</title>
    <!-- Memanggil CSS Playful Premium -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Dekorasi Latar Belakang (Awan / Blur Bulat) -->
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="container">
    
    <!-- 1. Bubble Navbar -->
    <nav class="bubble-nav">
        <div class="nav-brand">
            <span class="emoji-sparkle">✨</span> UNIVERSITAS PAMULANG
        </div>
        <div class="nav-badge">
            🚀 <?= $total_students ?> Active Students
        </div>
    </nav>

    <!-- 2. Hero Section (Judul Besar Lucu & Profesional) -->
    <header class="hero-playful">
        <div class="hero-tag">🌟 Student Directory 2026</div>
        <h1>Manage <span>Students</span><br>Universitas Pamulang</h1>
        <p>PHP and Google Sheets-Based Student Data Application.</p>
    </header>

    <!-- 3. Plush Search Bar -->
    <div class="search-container">
        <form method="GET" action="" class="search-plush">
            <input type="text" name="search" class="search-input" placeholder="Ketik nama atau kelas di sini..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    <!-- 4. STUDENT CARD GRID (Tampilan Kotak-kotak Keren) -->
    <main class="student-grid">
        <?php if ($error_message): ?>
            <!-- Error State -->
            <div class="empty-candy">
                <h2>🥺</h2>
                <p>Ups! Koneksi ke Google Sheets terputus.<br><?= htmlspecialchars($error_message) ?></p>
            </div>
        <?php elseif (empty($values) || count($values) <= 1): ?>
            <!-- Empty State -->
            <div class="empty-candy">
                <h2>📭</h2>
                <p>Belum ada data mahasiswa nih.</p>
            </div>
        <?php else: ?>
            <?php 
            $data_found = false;
            foreach ($values as $index => $row) {
                if ($index == 0) continue; 
                
                $nama = isset($row[0]) ? $row[0] : '-';
                $kelas = isset($row[1]) ? $row[1] : '-';
                $wa = isset($row[2]) ? $row[2] : '-';
                $email = isset($row[3]) ? $row[3] : '-';

                // Fitur Filter Search
                if ($search_query !== '') {
                    if (strpos(strtolower($nama), $search_query) === false && strpos(strtolower($kelas), $search_query) === false) continue;
                }

                $data_found = true;
                
                // Format Link WA
                $wa_link = (strpos($wa, '0') === 0) ? '628' . substr($wa, 1) : $wa;
                $sheet_row = $index + 1;
                
                // Ambil huruf pertama untuk Avatar Lucu
                $inisial = strtoupper(substr($nama, 0, 1));
            ?>
            
            <!-- STUDENT CARD INDIVIDUAL -->
            <div class="student-card">
                <!-- Avatar Memantul -->
                <div class="card-avatar">
                    <?= htmlspecialchars($inisial) ?>
                </div>
                
                <!-- Info Mahasiswa -->
                <h3 class="card-name"><?= htmlspecialchars($nama) ?></h3>
                <div class="card-class">📚 <?= htmlspecialchars($kelas) ?></div>
                <div style="font-size: 0.85rem; color: #8D99AE; font-weight: 500; margin-bottom: 10px;">
                    ✉️ <?= htmlspecialchars($email) ?>
                </div>
                
                <!-- Tombol Aksi di dalam Card -->
                <div class="card-actions">
                    <a href="https://wa.me/<?= htmlspecialchars($wa_link) ?>" target="_blank" class="btn-action btn-wa">
                        Chat
                    </a>
                    <a href="edit.php?row=<?= $sheet_row ?>" class="btn-action btn-edit">
                        Edit
                    </a>
                    <a href="delete.php?row=<?= $sheet_row ?>" class="btn-action btn-del">
                        Del
                    </a>
                </div>
            </div>
            <?php } ?>
            
            <?php if (!$data_found): ?>
                <!-- Not Found State -->
                <div class="empty-candy">
                    <h2>🕵️‍♀️</h2>
                    <p>Hmm, mahasiswa yang kamu cari tidak ditemukan.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

</div>

<!-- 5. Toast Notification (Bubble Animasi) -->
<?php if ($toast_message): ?>
    <div id="toastNotif" class="toast-bubble">
        <?= htmlspecialchars($toast_message) ?>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toast = document.getElementById("toastNotif");
            // Efek memantul masuk
            setTimeout(function() { toast.classList.add("show"); }, 100);
            // Menghilang setelah 4 detik
            setTimeout(function() { toast.classList.remove("show"); }, 4000);
        });
    </script>
<?php endif; ?>

</body>
</html>