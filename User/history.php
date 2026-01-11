<?php
require_once '../Config/koneksi.php';
include 'header.php';

$nis = $_SESSION['nis'];

/* =========================
   QUERY RIWAYAT PEMINJAMAN
========================= */
$queryHistory = $conn->prepare("
    SELECT 
        p.kode_pinjam,
        p.tgl_pinjam,
        p.estimasi_pinjam,
        p.status AS status_peminjaman,

        pg.tgl_kembali,
        pg.kondisi_buku,
        pg.denda,
        pg.status AS status_pengembalian,
        pg.pembayaran,

        b.judul_buku,
        b.cover
    FROM peminjaman p
    LEFT JOIN detail_peminjaman dp 
        ON p.kode_pinjam = dp.kode_pinjam
    LEFT JOIN buku b 
        ON dp.kode_buku = b.kode_buku
    LEFT JOIN pengembalian pg 
        ON p.kode_pinjam = pg.kode_pinjam
    WHERE p.nis = :nis
    ORDER BY p.tgl_pinjam DESC
");

$queryHistory->bindParam(':nis', $nis);
$queryHistory->execute();
$history = $queryHistory->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   STATISTIK
========================= */
$queryTotal = $conn->prepare("
    SELECT COUNT(DISTINCT kode_pinjam) AS total 
    FROM peminjaman 
    WHERE nis = :nis
");
$queryTotal->bindParam(':nis', $nis);
$queryTotal->execute();
$totalPinjaman = $queryTotal->fetch(PDO::FETCH_ASSOC)['total'];

$queryLate = $conn->prepare("
    SELECT 
        SUM(
            CASE 
                WHEN pg.tgl_kembali > p.estimasi_pinjam 
                THEN DATEDIFF(pg.tgl_kembali, p.estimasi_pinjam) 
                ELSE 0 
            END
        ) AS total_hari
    FROM peminjaman p
    LEFT JOIN pengembalian pg 
        ON p.kode_pinjam = pg.kode_pinjam
    WHERE p.nis = :nis
");
$queryLate->bindParam(':nis', $nis);
$queryLate->execute();
$totalTerlambat = $queryLate->fetch(PDO::FETCH_ASSOC)['total_hari'] ?? 0;
?>

<section class="conten ios-mobile">

    <div class="mobile-header">
        <h1 class="ios-title">Riwayat Peminjaman</h1>
    </div>

    <div class="mobile-stats">
        <div class="stat-item">
            <div class="stat-value"><?= $totalPinjaman ?></div>
            <div class="stat-label">Buku Dipinjam</div>
        </div>
        <div class="stat-item">
            <div class="stat-value text-danger"><?= $totalTerlambat ?></div>
            <div class="stat-label">Hari Terlambat</div>
        </div>
    </div>

    <div class="mobile-list">
        <?php if (empty($history)): ?>
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <p>Belum ada riwayat peminjaman</p>
            </div>
        <?php else: ?>
            <?php foreach ($history as $h):
                $isLate = $h['tgl_kembali'] && strtotime($h['tgl_kembali']) > strtotime($h['estimasi_pinjam']);
            ?>
                <div class="list-item shadow-sm rounded-3 mb-4 border-1">
                    <img src="../Assets/uploads/<?= htmlspecialchars($h['cover'] ?? 'default-cover.jpg') ?>"
                         class="ms-2 item-cover">

                    <div class="item-content">
                        <div class="item-header">
                            <h3><?= htmlspecialchars($h['judul_buku']) ?></h3>
                            <span class="status-badge <?= ($h['status_pengembalian'] === 'Lunas') ? 'returned' : 'borrowed' ?>">
                                <?= $h['status_pengembalian'] ?? 'Dipinjam' ?>
                            </span>
                        </div>

                        <div class="item-meta">
                            <div class="meta-date">
                                <i class="fas fa-calendar-alt"></i>
                                <?= date('d M Y', strtotime($h['tgl_pinjam'])) ?>
                            </div>

                            <?php if ($isLate): ?>
                                <div class="meta-late">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Terlambat <?= $h['denda'] ? 'Rp ' . number_format($h['denda'], 0, ',', '.') : '' ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
