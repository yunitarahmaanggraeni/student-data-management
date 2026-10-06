<?php
// --- BACKEND LOGIC: TETAP AMAN 100% ---
require_once __DIR__ . '/config/config.php';

if (!isset($_GET['row'])) {
    header('Location: index.php');
    exit;
}

$rowId = $_GET['row'];
$range = 'Sheet1!A' . $rowId . ':D' . $rowId; 
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $kelas = trim($_POST['kelas']);
    $wa = trim($_POST['wa']);
    $email = trim($_POST['email']);

    $body = new \Google_Service_Sheets_ValueRange([
        'values' => [[$nama, $kelas, $wa, $email]]
    ]);
    $params = ['valueInputOption' => 'RAW'];

    try {
        $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
        header('Location: index.php?status=edit_sukses');
        exit;
    } catch (Exception $e) {
        $error_message = "Gagal memperbarui data: " . $e->getMessage();
    }
}

try {
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $data = $response->getValues();
    if (empty($data)) die("Record not found.");
    $mahasiswa = $data[0];
} catch (Exception $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa -  Universitas Pamulang</title>
    <!-- Memanggil CSS Playful Premium -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Desain Khusus Form Bouncy */
        .form-card {
            background: var(--card-bg); border-radius: 32px; padding: 40px;
            box-shadow: 0 10px 30px rgba(43, 45, 66, 0.04); border: 2px solid #FFFFFF;
            max-width: 600px; margin: 0 auto; position: relative; z-index: 10;
            animation: slideUp 0.8s 0.1s var(--spring) forwards; opacity: 0;
        }
        .form-group { margin-bottom: 24px; text-align: left; }
        .form-label { display: block; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; font-size: 0.95rem; }
        .form-input {
            width: 100%; background: #F7F9FC; border: 2px solid transparent;
            padding: 16px 24px; border-radius: 20px; font-size: 1rem; color: var(--text-dark);
            font-weight: 600; outline: none; transition: var(--transition);
        }
        .form-input:focus { 
            border-color: var(--pink-blush); background: #FFFFFF; 
            box-shadow: var(--shadow-candy); transform: translateY(-3px); 
        }
        .btn-save { 
            background: var(--pink-candy); color: white; border: none; 
            padding: 18px 32px; border-radius: 100px; font-weight: 800; font-size: 1.1rem; 
            cursor: pointer; transition: var(--transition); 
            box-shadow: 0 8px 20px rgba(255, 107, 158, 0.4); width: 100%; margin-top: 10px; 
        }
        .btn-save:hover { transform: translateY(-5px) scale(1.02); background: var(--pink-deep); }
        .btn-back { 
            display: block; text-align: center; margin-top: 24px; color: var(--text-gray); 
            font-weight: 700; text-decoration: none; transition: var(--transition); 
        }
        .btn-back:hover { color: var(--pink-candy); transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="container">
    <nav class="bubble-nav">
        <div class="nav-brand"><span class="emoji-sparkle">✨</span> UNIVERSITAS PAMULANG</div>
        <div class="nav-badge">✏️ Edit Mode</div>
    </nav>

    <header class="hero-playful" style="margin-bottom: 40px;">
        <h1>Update <span>Profile</span></h1>
    </header>

    <div class="form-card">
        <?php if ($error_message): ?>
            <p style="color: #EF4444; font-weight: 600; text-align: center; margin-bottom: 20px;">
                Ups! <?= htmlspecialchars($error_message) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Nama Lengkap 🎀</label>
                <input type="text" name="nama" class="form-input" value="<?= htmlspecialchars($mahasiswa[0] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kelas 📚</label>
                <input type="text" name="kelas" class="form-input" value="<?= htmlspecialchars($mahasiswa[1] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">No. WhatsApp 💬</label>
                <input type="text" name="wa" class="form-input" value="<?= htmlspecialchars($mahasiswa[2] ?? '') ?>" required pattern="[0-9+]+">
            </div>
            <div class="form-group">
                <label class="form-label">Email ✉️</label>
                <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($mahasiswa[3] ?? '') ?>" required>
            </div>
            
            <button type="submit" class="btn-save">Simpan Perubahan 🚀</button>
            <a href="index.php" class="btn-back">Batal & Kembali</a>
        </form>
    </div>
</div>

</body>
</html>