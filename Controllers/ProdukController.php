<?php

include '../Config/koneksi.php';

/* =========================
   SIMPAN DATA
========================= */

if(isset($_POST['simpan'])){

    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];

    mysqli_query($conn,"
        INSERT INTO dim_produk
        (
            kode_produk,
            nama_produk,
            kategori,
            harga
        )
        VALUES
        (
            '$kode',
            '$nama',
            '$kategori',
            '$harga'
        )
    ");

    header("Location: ../Master Data/data_produk.php");
    exit;
}

/* =========================
   UPDATE DATA
========================= */

if(isset($_POST['update'])){

    $id = $_POST['id_produk'];

    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];

    mysqli_query($conn,"
        UPDATE dim_produk
        SET
            kode_produk='$kode',
            nama_produk='$nama',
            kategori='$kategori',
            harga='$harga'
        WHERE id_produk='$id'
    ");

    header("Location: ../Master Data/data_produk.php");
    exit;
}

/* =========================
   HAPUS DATA
========================= */

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"
        DELETE FROM dim_produk
        WHERE id_produk='$id'
    ");

    header("Location: ../Master Data/data_produk.php");
    exit;
}

?>