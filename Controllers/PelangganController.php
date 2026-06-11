<?php
include_once "../Config/koneksi.php";

/** @var mysqli $conn */

if (isset($_POST['simpan'])) {

    $kode_pelanggan = $_POST['kode_pelanggan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $kota = $_POST['kota'];

    mysqli_query(
        $conn,
        "INSERT INTO dim_pelanggan
        (
            kode_pelanggan,
            nama_pelanggan,
            jenis_kelamin,
            kota
        )
        VALUES
        (
            '$kode_pelanggan',
            '$nama_pelanggan',
            '$jenis_kelamin',
            '$kota'
        )"
    );

    header("Location: ../Master Data/data_pelanggan.php?success=simpan");
    exit;
}


if (isset($_POST['update'])) {

    $id_pelanggan = $_POST['id_pelanggan'];
    $kode_pelanggan = $_POST['kode_pelanggan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $kota = $_POST['kota'];

    mysqli_query(
        $conn,
        "UPDATE dim_pelanggan
         SET
            kode_pelanggan = '$kode_pelanggan',
            nama_pelanggan = '$nama_pelanggan',
            jenis_kelamin = '$jenis_kelamin',
            kota = '$kota'
         WHERE id_pelanggan = '$id_pelanggan'"
    );

    header("Location: ../Master Data/data_pelanggan.php?success=update");
    exit;
}


if (isset($_GET['hapus'])) {

    $id_pelanggan = $_GET['hapus'];

    mysqli_query(
        $conn,
        "DELETE FROM dim_pelanggan
         WHERE id_pelanggan = '$id_pelanggan'"
    );

    header("Location: ../Master Data/data_pelanggan.php?success=hapus");
    exit;
}
?>