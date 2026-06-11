<?php

include_once "../Config/koneksi.php";

/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/

if (isset($_POST['simpan'])) {

    $id_produk = $_POST['id_produk'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_waktu = $_POST['id_waktu'];
    $jumlah = $_POST['jumlah'];

    // Ambil harga dari dim_produk
    $produk = mysqli_query(
        $conn,
        "SELECT harga
         FROM dim_produk
         WHERE id_produk = '$id_produk'"
    );

    $dataProduk = mysqli_fetch_assoc($produk);

    $harga_satuan = $dataProduk['harga'];

    $total_harga = $jumlah * $harga_satuan;

    mysqli_query(
        $conn,
        "INSERT INTO fact_penjualan
        (
            id_produk,
            id_pelanggan,
            id_waktu,
            jumlah,
            harga_satuan,
            total_harga
        )
        VALUES
        (
            '$id_produk',
            '$id_pelanggan',
            '$id_waktu',
            '$jumlah',
            '$harga_satuan',
            '$total_harga'
        )"
    );
    header(
        "Location: ../Transaksi/data_penjualan.php?success=simpan"
    );
    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if (isset($_POST['update'])) {

    $id_penjualan = $_POST['id_penjualan'];

    $id_produk = $_POST['id_produk'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_waktu = $_POST['id_waktu'];

    $jumlah = $_POST['jumlah'];

    // Ambil harga terbaru dari produk
    $produk = mysqli_query(
        $conn,
        "SELECT harga
         FROM dim_produk
         WHERE id_produk = '$id_produk'"
    );

    $dataProduk = mysqli_fetch_assoc($produk);

    $harga_satuan = $dataProduk['harga'];

    $total_harga = $jumlah * $harga_satuan;

    mysqli_query(
        $conn,
        "UPDATE fact_penjualan
         SET
            id_produk = '$id_produk',
            id_pelanggan = '$id_pelanggan',
            id_waktu = '$id_waktu',
            jumlah = '$jumlah',
            harga_satuan = '$harga_satuan',
            total_harga = '$total_harga'
         WHERE id_penjualan = '$id_penjualan'"
    );

    header("Location: ../Transaksi/data_penjualan.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

if (isset($_GET['hapus'])) {

    $id_penjualan = $_GET['hapus'];

    mysqli_query(
        $conn,
        "DELETE FROM fact_penjualan
         WHERE id_penjualan = '$id_penjualan'"
    );

    header("Location: ../Transaksi/data_penjualan.php");
    exit;
}