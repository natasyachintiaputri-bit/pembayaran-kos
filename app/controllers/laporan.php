
<?php

require_once __DIR__ . '/../models/laporan.php';

class LaporanController
{
    public function index()
    {
        $ringkasan = Laporan::ringkasan();
        $totalPembayaran = Laporan::totalPembayaran();
        $dataPembayaran = Laporan::semuaPembayaran();

        require_once __DIR__ . '/../views/laporan/index.php';
    }
}
?>
