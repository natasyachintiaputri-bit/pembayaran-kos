<?php

require_once __DIR__ . '/../../config/database.php';

class Pembayaran
{
    // Mengambil seluruh pembayaran untuk admin
    public static function semua()
    {
        global $conn;

        $sql = "SELECT
                    pembayaran.*,
                    penghuni.nama AS nama_penghuni,
                    tagihan.jenis,
                    tagihan.bulan,
                    tagihan.tahun
                FROM pembayaran
                JOIN tagihan ON pembayaran.tagihan_id = tagihan.id
                JOIN penghuni ON tagihan.penghuni_id = penghuni.id
                ORDER BY pembayaran.created_at DESC";

        $result = mysqli_query($conn, $sql);

        return $result
            ? mysqli_fetch_all($result, MYSQLI_ASSOC)
            : [];
    }

    // Mengambil riwayat pembayaran milik penghuni
    public static function riwayat($penghuni_id)
    {
        global $conn;

        $query = mysqli_prepare(
            $conn,
            "SELECT
                pembayaran.*,
                tagihan.jenis,
                tagihan.bulan,
                tagihan.tahun
             FROM pembayaran
             JOIN tagihan ON pembayaran.tagihan_id = tagihan.id
             WHERE tagihan.penghuni_id = ?
             ORDER BY pembayaran.created_at DESC"
        );

        if (!$query) {
            return [];
        }

        mysqli_stmt_bind_param($query, "i", $penghuni_id);
        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);

        $data = $result
            ? mysqli_fetch_all($result, MYSQLI_ASSOC)
            : [];

        mysqli_stmt_close($query);

        return $data;
    }

    // Mengambil ringkasan tagihan dan pembayaran penghuni
    public static function ringkasanPenghuni($penghuni_id)
    {
        global $conn;

        $hasil = [
            'total_tagihan' => 0,
            'total_pembayaran' => 0,
            'status_pembayaran' => 'Belum Ada'
        ];

        if ($penghuni_id <= 0) {
            return $hasil;
        }

        $sql = "SELECT
                    (
                        SELECT COALESCE(SUM(nominal), 0)
                        FROM tagihan
                        WHERE penghuni_id = ?
                    ) AS total_tagihan,

                    (
                        SELECT COALESCE(SUM(p.nominal), 0)
                        FROM pembayaran p
                        JOIN tagihan t ON p.tagihan_id = t.id
                        WHERE t.penghuni_id = ?
                        AND p.status_verifikasi = 'Diterima'
                    ) AS total_pembayaran,

                    (
                        SELECT COUNT(*)
                        FROM tagihan
                        WHERE penghuni_id = ?
                    ) AS jumlah_tagihan,

                    (
                        SELECT COUNT(*)
                        FROM tagihan
                        WHERE penghuni_id = ?
                        AND status = 'Lunas'
                    ) AS jumlah_lunas,

                    (
                        SELECT COUNT(*)
                        FROM tagihan
                        WHERE penghuni_id = ?
                        AND status = 'Menunggu Konfirmasi'
                    ) AS jumlah_menunggu";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            return $hasil;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "iiiii",
            $penghuni_id,
            $penghuni_id,
            $penghuni_id,
            $penghuni_id,
            $penghuni_id
        );

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return $hasil;
        }

        $result = mysqli_stmt_get_result($stmt);
        $data = $result
            ? mysqli_fetch_assoc($result)
            : null;

        mysqli_stmt_close($stmt);

        if (!$data) {
            return $hasil;
        }

        $jumlahTagihan = (int) $data['jumlah_tagihan'];
        $jumlahLunas = (int) $data['jumlah_lunas'];
        $jumlahMenunggu = (int) $data['jumlah_menunggu'];

        // Menentukan status pembayaran penghuni
        if ($jumlahTagihan == 0) {
            $status = 'Belum Ada';
        } elseif ($jumlahMenunggu > 0) {
            $status = 'Menunggu Konfirmasi';
        } elseif ($jumlahLunas == $jumlahTagihan) {
            $status = 'Lunas';
        } elseif ($jumlahLunas > 0) {
            $status = 'Sebagian Lunas';
        } else {
            $status = 'Belum Lunas';
        }

        return [
            'total_tagihan' => (float) $data['total_tagihan'],
            'total_pembayaran' => (float) $data['total_pembayaran'],
            'status_pembayaran' => $status
        ];
    }

    // Mengambil rekening pemilik kos
    public static function rekening()
    {
        global $conn;

        $result = mysqli_query(
            $conn,
            "SELECT * FROM rekening ORDER BY id DESC"
        );

        return $result
            ? mysqli_fetch_all($result, MYSQLI_ASSOC)
            : [];
    }

    // Mengajukan pembayaran dari penghuni
    public static function buatPengajuan(
        $tagihan_id,
        $penghuni_id,
        $metode,
        $bukti = null
    ) {
        global $conn;

        mysqli_begin_transaction($conn);

        try {
            $cek = mysqli_prepare(
                $conn,
                "SELECT id, nominal, status
                 FROM tagihan
                 WHERE id = ? AND penghuni_id = ?
                 FOR UPDATE"
            );

            mysqli_stmt_bind_param(
                $cek,
                "ii",
                $tagihan_id,
                $penghuni_id
            );

            mysqli_stmt_execute($cek);

            $tagihan = mysqli_fetch_assoc(
                mysqli_stmt_get_result($cek)
            );

            mysqli_stmt_close($cek);

            if (!$tagihan || $tagihan['status'] != 'Belum Bayar') {
                throw new Exception(
                    "Tagihan tidak tersedia untuk dibayar."
                );
            }

            // Mencegah pengajuan pembayaran ganda yang masih menunggu
            $cekPembayaran = mysqli_prepare(
                $conn,
                "SELECT id
                 FROM pembayaran
                 WHERE tagihan_id = ?
                 AND status_verifikasi = 'Menunggu'
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $cekPembayaran,
                "i",
                $tagihan_id
            );

            mysqli_stmt_execute($cekPembayaran);

            $hasilCek = mysqli_stmt_get_result($cekPembayaran);

            $adaPembayaranMenunggu = mysqli_num_rows($hasilCek) > 0;

            mysqli_stmt_close($cekPembayaran);

            if ($adaPembayaranMenunggu) {
                throw new Exception(
                    "Pembayaran masih menunggu verifikasi."
                );
            }

            $nominal = $tagihan['nominal'];

            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO pembayaran
                (
                    tagihan_id,
                    metode,
                    nominal,
                    tanggal_bayar,
                    bukti_transfer,
                    status_verifikasi
                )
                VALUES (?, ?, ?, NOW(), ?, 'Menunggu')"
            );

            mysqli_stmt_bind_param(
                $insert,
                "isds",
                $tagihan_id,
                $metode,
                $nominal,
                $bukti
            );

            if (!mysqli_stmt_execute($insert)) {
                throw new Exception(
                    "Gagal menyimpan pembayaran."
                );
            }

            mysqli_stmt_close($insert);

            // Memperbarui status tagihan
            $update = mysqli_prepare(
                $conn,
                "UPDATE tagihan
                 SET status = 'Menunggu Konfirmasi'
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $update,
                "i",
                $tagihan_id
            );

            if (!mysqli_stmt_execute($update)) {
                throw new Exception(
                    "Gagal memperbarui status tagihan."
                );
            }

            mysqli_stmt_close($update);

            mysqli_commit($conn);

            return true;

        } catch (Throwable $e) {
            mysqli_rollback($conn);

            return false;
        }
    }

    // Verifikasi pembayaran oleh admin
    public static function verifikasi($id, $status, $admin_id)
    {
        global $conn;

        if (!in_array($status, ['Diterima', 'Ditolak'])) {
            return false;
        }

        mysqli_begin_transaction($conn);

        try {
            $cek = mysqli_prepare(
                $conn,
                "SELECT tagihan_id
                 FROM pembayaran
                 WHERE id = ?
                 AND status_verifikasi = 'Menunggu'
                 FOR UPDATE"
            );

            mysqli_stmt_bind_param($cek, "i", $id);
            mysqli_stmt_execute($cek);

            $data = mysqli_fetch_assoc(
                mysqli_stmt_get_result($cek)
            );

            mysqli_stmt_close($cek);

            if (!$data) {
                throw new Exception(
                    "Pembayaran tidak ditemukan atau sudah diverifikasi."
                );
            }

            // Memperbarui status pembayaran
            $updatePembayaran = mysqli_prepare(
                $conn,
                "UPDATE pembayaran
                 SET status_verifikasi = ?,
                     diverifikasi_oleh = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $updatePembayaran,
                "sii",
                $status,
                $admin_id,
                $id
            );

            if (!mysqli_stmt_execute($updatePembayaran)) {
                throw new Exception(
                    "Gagal memperbarui pembayaran."
                );
            }

            mysqli_stmt_close($updatePembayaran);

            // Memperbarui status tagihan
            $statusTagihan = ($status == 'Diterima')
                ? 'Lunas'
                : 'Belum Bayar';

            $updateTagihan = mysqli_prepare(
                $conn,
                "UPDATE tagihan
                 SET status = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $updateTagihan,
                "si",
                $statusTagihan,
                $data['tagihan_id']
            );

            if (!mysqli_stmt_execute($updateTagihan)) {
                throw new Exception(
                    "Gagal memperbarui status tagihan."
                );
            }

            mysqli_stmt_close($updateTagihan);

            mysqli_commit($conn);

            return true;

        } catch (Throwable $e) {
            mysqli_rollback($conn);

            return false;
        }
    }
}

?>