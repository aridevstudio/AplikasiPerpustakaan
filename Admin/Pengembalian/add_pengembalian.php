<?php
session_start();
require_once '../../Config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kode_pinjam   = $_POST['kode_pinjam'];
    $kondisi_buku  = $_POST['kondisi_buku'];
    $tgl_kembali   = date('Y-m-d');
    $denda         = 0;
    $pembayaran    = 'Tidak Ada';
    $status        = 'Lunas';

    // 🔹 Ambil estimasi pinjam
    $stmt = $conn->prepare("
        SELECT estimasi_pinjam
        FROM peminjaman
        WHERE kode_pinjam = :kode_pinjam
    ");
    $stmt->execute(['kode_pinjam' => $kode_pinjam]);
    $peminjaman = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$peminjaman) {
        $_SESSION['message'] = "Kode peminjaman tidak ditemukan.";
        header('Location: pengembalian.php');
        exit;
    }

    $estimasi_pinjam = $peminjaman['estimasi_pinjam'];

    // 🔹 Hitung keterlambatan
    $hari_terlambat = max(
        floor((strtotime($tgl_kembali) - strtotime($estimasi_pinjam)) / 86400),
        0
    );

    if ($hari_terlambat > 0) {
        $denda += $hari_terlambat * 5000;
        $status = 'Belum Lunas';
    }

    // 🔹 Denda kondisi buku
    if ($kondisi_buku === 'rusak') {
        $denda += 20000;
        $status = 'Belum Lunas';
    } elseif ($kondisi_buku === 'hilang') {
        $denda += 50000;
        $status = 'Belum Lunas';
    }

    // 🔹 Generate kode_kembali
    $lastKode = $conn->query("
        SELECT kode_kembali
        FROM pengembalian
        ORDER BY kode_kembali DESC
        LIMIT 1
    ")->fetch(PDO::FETCH_ASSOC);

    $no = $lastKode ? (int)substr($lastKode['kode_kembali'], 2) + 1 : 1;
    $kode_kembali = 'KB' . str_pad($no, 3, '0', STR_PAD_LEFT);

    try {
        $conn->beginTransaction();

        // 1️⃣ Insert pengembalian
        $stmt = $conn->prepare("
            INSERT INTO pengembalian
            (kode_kembali, kode_pinjam, tgl_kembali, denda, pembayaran)
            VALUES
            (:kode_kembali, :kode_pinjam, :tgl_kembali, :denda, :pembayaran)
        ");
        $stmt->execute([
            'kode_kembali' => $kode_kembali,
            'kode_pinjam'  => $kode_pinjam,
            'tgl_kembali'  => $tgl_kembali,
            'denda'        => $denda,
            'pembayaran'   => $pembayaran
        ]);

        // 2️⃣ Update kondisi buku
        $stmt = $conn->prepare("
            UPDATE detail_peminjaman
            SET kondisi_buku_pinjam = :kondisi
            WHERE kode_pinjam = :kode_pinjam
        ");
        $stmt->execute([
            'kondisi'     => $kondisi_buku,
            'kode_pinjam' => $kode_pinjam
        ]);

        // 3️⃣ Update stok (kecuali hilang)
        if ($kondisi_buku !== 'hilang') {
            $stmt = $conn->prepare("
                UPDATE buku b
                JOIN detail_peminjaman dp
                    ON b.kode_buku = dp.kode_buku
                SET b.stok = b.stok + 1
                WHERE dp.kode_pinjam = :kode_pinjam
            ");
            $stmt->execute(['kode_pinjam' => $kode_pinjam]);
        }

        // 4️⃣ Update status peminjaman
        $stmt = $conn->prepare("
            UPDATE peminjaman
            SET status = 'Dikembalikan'
            WHERE kode_pinjam = :kode_pinjam
        ");
        $stmt->execute(['kode_pinjam' => $kode_pinjam]);

        $conn->commit();

        $_SESSION['message'] =
            "Pengembalian berhasil | Kode: $kode_kembali | Denda: Rp " .
            number_format($denda, 0, ',', '.');

        header('Location: pengembalian.php');
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['message'] = 'Gagal: ' . $e->getMessage();
        header('Location: pengembalian.php');
        exit;
    }
}

// Data peminjaman belum dikembalikan
$peminjaman = $conn->query("
    SELECT kode_pinjam
    FROM peminjaman
    WHERE kode_pinjam NOT IN (SELECT kode_pinjam FROM pengembalian)
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <form action="add_pengembalian.php" method="POST">
        <!-- Pencarian Kode Peminjaman -->
        <div class="mb-3">
            <label for="kode_pinjam" class="form-label">Kode Peminjaman</label>
            <input autocomplete="off" type="text" id="kode_pinjam" name="kode_pinjam" class="form-control" placeholder="Cari Kode Pnjam.." required>
            <div id="search_results" class="mt-2"></div> <!-- Menampilkan hasil pencarian -->
        </div>

        <!-- Kondisi Buku -->
        <div class="mb-3">
            <label for="kondisi_buku" class="form-label">Kondisi Buku</label>
            <select name="kondisi_buku" id="kondisi_buku" class="form-select" required>
                <option value="bagus">Bagus</option>
                <option value="rusak">Rusak</option>
                <option value="hilang">Hilang</option>
            </select>
        </div>
        <div class="d-flex justify-content-end mt-4 rounded-3">
            <button type="reset" class="btn btn-danger me-2">Reset</button>
            <button type="submit" class="btn btn-primary">Proses</button>
        </div>

    </form>
</div>