<?php
// Memanggil library Google yang sudah di-download oleh Composer
require_once __DIR__ . '/../vendor/autoload.php';

// 1. MASUKKAN SPREADSHEET ID KAMU DI SINI
$spreadsheetId = '1WfX1MFIDw2Gi0iSuaWRT2L9u6wvcaG2HgK2nQpKYkxA';

// 2. Inisialisasi Google Client (Mempersiapkan robotnya)
$client = new \Google_Client();
$client->setApplicationName('Universitas Pamulang App');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');

// 3. Memberikan KTP/Izin ke robot (Arahkan ke file credentials.json)
$client->setAuthConfig(__DIR__ . '/../credentials/credentials.json');

// 4. Menjalankan service Google Sheets
$service = new \Google_Service_Sheets($client);
?>