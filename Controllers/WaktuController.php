<?php

include_once "../config/koneksi.php";

if (isset($_POST['generate'])) {

    $tanggal_awal = $_POST['tanggal_awal'];
    $tanggal_akhir = $_POST['tanggal_akhir'];

    $current = strtotime($tanggal_awal);
    $end = strtotime($tanggal_akhir);

    while ($current <= $end) {

        $tanggal = date("Y-m-d", $current);

        $tahun = date("Y", $current);
        $bulan = date("n", $current);

        $nama_bulan = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember"
        ];

        $bulan_nama = $nama_bulan[$bulan];

        $kuartal = ceil($bulan / 3);

        $cek = mysqli_query(
            $conn,
            "SELECT * FROM dim_waktu
             WHERE tanggal='$tanggal'"
        );

        if (mysqli_num_rows($cek) == 0) {

            mysqli_query(
                $conn,
                "INSERT INTO dim_waktu
                (tanggal,tahun,bulan,bulan_nama,kuartal)
                VALUES
                (
                    '$tanggal',
                    '$tahun',
                    '$bulan',
                    '$bulan_nama',
                    '$kuartal'
                )"
            );
        }

        $current = strtotime("+1 day", $current);
    }

    header(
        "Location: ../Master Data/data_waktu.php?success=generate"
    );
    exit;
}