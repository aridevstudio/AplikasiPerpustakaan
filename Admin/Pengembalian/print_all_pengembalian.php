<?php
require_once '../../Config/koneksi.php';

// Ambil seluruh data pengembalian
$stmt = $conn->prepare("
    SELECT 
        pg.kode_kembali,
        pg.tgl_kembali,
        pg.kode_pinjam,
        pg.denda,
        pg.pembayaran,
        pg.status,

        p.tgl_pinjam,
        p.estimasi_pinjam,

        a.nama AS nama_anggota,
        a.no_telp,

        GROUP_CONCAT(DISTINCT b.judul_buku SEPARATOR ', ') AS judul_buku,
        GROUP_CONCAT(DISTINCT dp.kondisi_buku_pinjam SEPARATOR ', ') AS kondisi_buku

    FROM pengembalian pg
    JOIN peminjaman p 
        ON pg.kode_pinjam = p.kode_pinjam
    JOIN anggota a 
        ON p.nim = a.nim
    JOIN detail_peminjaman dp 
        ON p.kode_pinjam = dp.kode_pinjam
    JOIN buku b 
        ON dp.kode_buku = b.kode_buku

    GROUP BY pg.kode_kembali
    ORDER BY pg.tgl_kembali DESC
");

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total penghasilan dari denda
$totalDenda = 0;
foreach ($data as $row) {
    $totalDenda += $row['denda'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Semua Pengembalian</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table th, table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    table th {
      background-color: #f2f2f2;
    }
    .text-center {
      text-align: center;
    }
    .text-right {
      text-align: right;
    }
    .summary {
      margin-top: 20px;
      font-size: 1.1em;
    }
    .summary span {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h2 class="text-center">Laporan Seluruh Pengembalian</h2>
  <table>
    <thead>
      <tr>
        <th>Kode</th>
        <th>Nama Anggota</th>
        <th>Judul Buku</th>
        <th>Tanggal Kembali</th>
        <th>Kondisi Buku</th>
        <th>Denda</th>
        <th>Status</th>
        <th>Pembayaran</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $row): ?>
      <tr>
        <td><?= $row['kode_kembali']; ?></td>
        <td><?= $row['nama_anggota']; ?></td>
        <td><?= $row['judul_buku']; ?></td>
        <td><?= date('d-m-Y H:i', strtotime($row['tgl_kembali'])); ?></td>
        <td><?= $row['kondisi_buku']; ?></td>
        <td>Rp<?= number_format($row['denda'], 2, ',', '.'); ?></td>
        <td><?= $row['status']; ?></td>
        <td><?= $row['pembayaran']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="summary">
    <p>Total Penghasilan Denda: <span>Rp<?= number_format($totalDenda, 2, ',', '.'); ?></span></p>
  </div>

  <script>
    // Cetak otomatis saat halaman dibuka
    window.print();
  </script>
</body>
</html>
