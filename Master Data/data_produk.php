<?php

include '../Config/koneksi.php';

$edit = false;

if(isset($_GET['edit'])){

    $edit = true;

    $id = $_GET['edit'];

    $data_edit = mysqli_query($conn,"
        SELECT *
        FROM dim_produk
        WHERE id_produk='$id'
    ");

    $row_edit = mysqli_fetch_assoc($data_edit);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Produk</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#0F172A;
    color:#F8FAFC;
}

/* NAVBAR */

.navbar{
    background:#1E293B;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 30px;
}

.logo{
    font-size:22px;
    font-weight:bold;
    color:#3B82F6;
}

.navbar ul{
    list-style:none;
    display:flex;
    gap:15px;
}

.navbar ul li a{
    color:white;
    text-decoration:none;
    padding:10px 15px;
    border-radius:8px;
    transition:.3s;
}

.navbar ul li a:hover,
.active{
    background:#3B82F6;
}

/* CONTAINER */

.container{
    width:95%;
    margin:30px auto;
}

/* CARD */

.card{
    background:#1E293B;
    padding:25px;
    border-radius:15px;
    margin-bottom:25px;
    box-shadow:0 0 10px rgba(0,0,0,.3);
}

.card h2,
.card h3{
    margin-bottom:20px;
}

/* FORM */

label{
    display:block;
    margin-bottom:8px;
}

input,
select{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#334155;
    color:white;
    margin-bottom:15px;
}

button{
    background:#3B82F6;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
}

button:hover{
    background:#2563EB;
}

.btn-batal{
    background:#EF4444;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    border-radius:8px;
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#3B82F6;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #334155;
}

tr:hover{
    background:#334155;
}

/* BUTTON */

.btn-edit{
    background:#22C55E;
    color:white;
    padding:8px 12px;
    border-radius:5px;
    text-decoration:none;
}

.btn-hapus{
    background:#EF4444;
    color:white;
    padding:8px 12px;
    border-radius:5px;
    text-decoration:none;
}

</style>

</head>
<body>

<div class="navbar">

    <div class="logo">
        DATA WAREHOUSE RETAIL
    </div>

    <ul>
        <li><a href="../dashboard.php">Dashboard</a></li>
        <li><a href="data_produk.php" class="active">Produk</a></li>
        <li><a href="data_waktu.php">Waktu</a></li>
        <li><a href="data_pelanggan.php">Pelanggan</a></li>
        <li><a href="../Transaksi/data_penjualan.php">Penjualan</a></li>
    </ul>

</div>

<div class="container">

    <!-- FORM -->

    <div class="card">

        <h2>CRUD Data Produk</h2>

        <form
        method="POST"
        action="../Controllers/ProdukController.php">

            <?php if($edit){ ?>

                <input
                type="hidden"
                name="id_produk"
                value="<?= $row_edit['id_produk']; ?>">

            <?php } ?>

            <label>Kode Produk</label>

            <input
            type="text"
            name="kode_produk"
            value="<?= $edit ? $row_edit['kode_produk'] : ''; ?>"
            required>

            <label>Nama Produk</label>

            <input
            type="text"
            name="nama_produk"
            value="<?= $edit ? $row_edit['nama_produk'] : ''; ?>"
            required>

            <label>Kategori</label>

            <select name="kategori">
                <option value="Elektronik">Elektronik</option>
                <option value="Makanan">Makanan</option>
                <option value="Pakaian">Pakaian</option>
            </select>

            <label>Harga</label>

            <input
            type="number"
            name="harga"
            value="<?= $edit ? $row_edit['harga'] : ''; ?>"
            required>

            <?php if($edit){ ?>

                <button
                type="submit"
                name="update">
                Simpan
                </button>

                <a
                href="data_produk.php"
                class="btn-batal">
                Batal
                </a>

            <?php } else { ?>

                <button
                type="submit"
                name="simpan">
                Simpan Data
                </button>

            <?php } ?>

        </form>

    </div>

    <!-- TABEL -->

    <div class="card">

        <h3>Daftar Produk</h3>

        <table>

            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>

            <?php

            $data = mysqli_query($conn,"
                SELECT *
                FROM dim_produk
                ORDER BY id_produk ASC
            ");

            while($row = mysqli_fetch_assoc($data)){

            ?>

            <tr>

                <td><?= $row['id_produk']; ?></td>
                <td><?= $row['kode_produk']; ?></td>
                <td><?= $row['nama_produk']; ?></td>
                <td><?= $row['kategori']; ?></td>
                <td>Rp <?= number_format($row['harga'],0,',','.'); ?></td>

                <td>

                    <a
                    class="btn-edit"
                    href="?edit=<?= $row['id_produk']; ?>">
                    Edit
                    </a>

                    <a
                    class="btn-hapus"
                    href="../Controllers/ProdukController.php?hapus=<?= $row['id_produk']; ?>"
                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                    Hapus
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>