<?php
// --- BACKEND LOGIC: TETAP AMAN 100% ---
require_once __DIR__ . '/config/config.php';

if (!isset($_GET['row'])) {
    header('Location: index.php');
    exit;
}

$rowId = (int)$_GET['row'];
$range = 'Sheet1!A' . $rowId . ':D' . $rowId; 
$error_message = '';

try {
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $data = $response->getValues();
    if (empty($data)) die("Record not found.");
    $nama_mahasiswa = $data[0][0] ?? 'Unknown';
} catch (Exception $e) {
    die("Error fetching data: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $spreadsheet = $service->spreadsheets->get($spreadsheetId);
        $sheets = $spreadsheet->getSheets();
        $sheetInternalId = 0;
        
        foreach ($sheets as $sheet) {
            if ($sheet->getProperties()->getTitle() == 'Sheet1') {
                $sheetInternalId = $sheet->getProperties()->getSheetId();
                break;
            }
        }

        $deleteRequest = new \Google_Service_Sheets_Request([
            'deleteDimension' => [
                'range' => [
                    'sheetId' => $sheetInternalId,
                    'dimension' => 'ROWS',
                    'startIndex' => $rowId - 1,
                    'endIndex' => $rowId
                ]
            ]
        ]);

        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
            'requests' => [$deleteRequest]
        ]);

        $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
        header('Location: index.php?status=delete_sukses');
        exit;
    } catch (Exception $e) {
        $error_message = "Gagal menghapus data: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Mahasiswa - Universitas Pamulang</title>
    <title>Hapus Mahasiswa - Universitas Pamulang</title>
    <!-- Memanggil CSS Playful Premium -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .confirm-card {
            background: var(--card-bg); border-radius: 32px; padding: 50px 40px;
            box-shadow: var(--shadow-float); border: 2px solid #FFFFFF;
            max-width: 500px; margin: 15vh auto; text-align: center; position: relative; z-index: 10;
            animation: slideUp 0.8s var(--spring) forwards;
        }
        .confirm-emoji { font-size: 5rem; animation: bounce 2s infinite; margin-bottom: 20px; display: inline-block; }
        .confirm-title { font-size: 2rem; font-weight: 800; color: var(--text-dark); margin-bottom: 16px; line-height: 1.2; letter-spacing: -0.5px;}
        .confirm-title span { color: var(--pink-candy); }
        .confirm-desc { color: var(--text-gray); font-weight: 500; font-size: 1.1rem; margin-bottom: 40px; }
        
        .action-buttons { display: flex; gap: 16px; justify-content: center; }
        .btn-cancel { 
            flex: 1; padding: 16px; border-radius: 100px; font-weight: 700; color: var(--text-dark); 
            background: #F0F4F8; text-decoration: none; transition: var(--transition); border: none; 
        }
        .btn-cancel:hover { background: #E2E8F0; transform: translateY(-3px); }
        .btn-confirm-del { 
            flex: 1; padding: 16px; border-radius: 100px; font-weight: 700; color: white; 
            background: var(--pink-candy); border: none; cursor: pointer; transition: var(--transition); 
            box-shadow: 0 8px 20px rgba(255, 107, 158, 0.3); 
        }
        .btn-confirm-del:hover { background: var(--pink-deep); transform: translateY(-3px) scale(1.02); box-shadow: var(--shadow-candy); }
    </style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="container">
    <div class="confirm-card">
        <div class="confirm-emoji">🥺</div>
        <h2 class="confirm-title">Hapus <span><?= htmlspecialchars($nama_mahasiswa) ?></span>?</h2>
        
        <?php if ($error_message): ?>
            <p style="color: #EF4444; font-weight: 600; margin-bottom: 20px;"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <p class="confirm-desc">Data ini akan hilang selamanya dari sistem. Kamu yakin mau menghapusnya?</p>
        
        <form method="POST" action="">
            <div class="action-buttons">
                <a href="index.php" class="btn-cancel">Batal Aja</a>
                <button type="submit" class="btn-confirm-del">Ya, Hapus 🚀</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>