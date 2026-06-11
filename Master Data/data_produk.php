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

/* =========================
   RESET
========================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* =========================
   BODY
========================= */

body{
    background:#F5F1E8;
    color:#4A3428;
    min-height:100vh;
}

/* =========================
   NAVBAR
========================= */

.navbar{
    background:#6F4E37;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 30px;
    box-shadow:0 2px 10px rgba(0,0,0,0.15);
}

.logo{
    font-size:22px;
    font-weight:bold;
    color:#FFF8E7;
}

.navbar ul{
    list-style:none;
    display:flex;
    gap:12px;
}

.navbar ul li a{
    text-decoration:none;
    color:#FFF8E7;
    padding:10px 15px;
    border-radius:8px;
    transition:0.3s;
}

.navbar ul li a:hover{
    background:#A67B5B;
}

.active{
    background:#A67B5B;
    color:white !important;
}

/* =========================
   CONTAINER
========================= */

.container{
    width:95%;
    margin:30px auto;
}

/* =========================
   CARD
========================= */

.card{
    background:#FFF8E7;
    padding:25px;
    border-radius:15px;
    margin-bottom:25px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.card h2,
.card h3{
    color:#6F4E37;
    margin-bottom:20px;
}

/* =========================
   FORM
========================= */

label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#5C4033;
}

input,
select{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:1px solid #D2B48C;
    border-radius:8px;
    background:#FDF6EC;
    color:#4A3428;
    font-size:14px;
}

input:focus,
select:focus{
    outline:none;
    border:2px solid #A67B5B;
    background:white;
}

/* =========================
   BUTTON
========================= */

button{
    background: green;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
    font-weight:600;
}

button:hover{
    background:#6F4E37;
}

.btn-batal{
    display:inline-block;
    background: red;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    border-radius:8px;
    margin-left:10px;
    transition:0.3s;
}

.btn-batal:hover{
    background:#B85C38;
}

/* =========================
   TABLE
========================= */

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
}

table th{
    background:#8B5E3C;
    color:white;
    padding:14px;
    text-align:center;
}

table td{
    padding:12px;
    border-bottom:1px solid #EADBC8;
    text-align:center;
}

table tr:nth-child(even){
    background:#FAF6EF;
}

table tr:hover{
    background:#F3E5D0;
}

/* =========================
   ACTION BUTTON
========================= */

.btn-edit{
    background:green;
    color:white;
    text-decoration:none;
    padding:8px 5px;
    border-radius:6px;
    transition:0.3s;
    margin-right:15px;
}

.btn-edit:hover{
    background:#8B5E3C;
}

.btn-hapus{
    background: red;
    color:white;
    text-decoration:none;
    padding:8px 14px;
    border-radius:6px;
    transition:0.3s;
}

.btn-hapus:hover{
    background:#B85C38;
}

/* =========================
   PAGE TITLE
========================= */

.page-title{
    color:#6F4E37;
    margin-bottom:20px;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:768px){

    .navbar{
        flex-direction:column;
        gap:15px;
    }

    .navbar ul{
        flex-wrap:wrap;
        justify-content:center;
    }

    table{
        display:block;
        overflow-x:auto;
    }

    .container{
        width:98%;
    }

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

        <h2>Tambah Produk</h2>

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