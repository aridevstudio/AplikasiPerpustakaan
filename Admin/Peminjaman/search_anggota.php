<?php
require_once '../../Config/koneksi.php';

$query = $_GET['query'] ?? '';

$stmt = $conn->prepare("
    SELECT nis, nama 
    FROM anggota 
    WHERE (nis LIKE :query OR nama LIKE :query) AND status_mhs = 'Aktif'
");
$stmt->execute([':query' => "%$query%"]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
