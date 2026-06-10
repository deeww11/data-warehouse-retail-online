<?php
$conn = mysqli_connect("localhost","root","","dw_retail");

if(isset($_POST['simpan'])){

    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];

    mysqli_query($conn,"
        INSERT INTO produk
        (kode_produk,nama_produk,kategori,harga)
        VALUES
        ('$kode','$nama','$kategori','$harga')
    ");
}

if(isset($_POST['update'])){

    $id = $_POST['id_produk'];
    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];

    mysqli_query($conn,"
        UPDATE produk
        SET
        kode_produk='$kode',
        nama_produk='$nama',
        kategori='$kategori',
        harga='$harga'
        WHERE id_produk='$id'
    ");
}

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"
        DELETE FROM produk
        WHERE id_produk='$id'
    ");
}

$edit = false;

if(isset($_GET['edit'])){

    $edit = true;

    $id = $_GET['edit'];

    $data_edit = mysqli_query($conn,"
        SELECT * FROM produk
        WHERE id_produk='$id'
    ");

    $row_edit = mysqli_fetch_assoc($data_edit);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Produk</title>

    <style>

        body{
            font-family: Arial;
            margin:40px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table,th,td{
            border:1px solid #ccc;
        }

        th,td{
            padding:10px;
        }

        input,select{
            width:100%;
            padding:8px;
            margin-bottom:10px;
        }

        button{
            padding:10px 20px;
        }

    </style>

</head>
<body>

<h2>CRUD Data Produk</h2>

<form method="POST">

<?php if($edit){ ?>

<input
type="hidden"
name="id_produk"
value="<?= $row_edit['id_produk']; ?>"
>

<?php } ?>

<label>Kode Produk</label>

<input
type="text"
name="kode_produk"
value="<?= $edit ? $row_edit['kode_produk'] : ''; ?>"
required
>

<label>Nama Produk</label>

<input
type="text"
name="nama_produk"
value="<?= $edit ? $row_edit['nama_produk'] : ''; ?>"
required
>

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
required
>

<?php if($edit){ ?>

<button type="submit" name="update">
Update Data
</button>

<a href="data_produk.php">
Batal
</a>

<?php } else { ?>

<button type="submit" name="simpan">
Simpan Data
</button>

<?php } ?>

</form>

<hr>

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
SELECT * FROM produk
ORDER BY id_produk DESC
");

while($row = mysqli_fetch_assoc($data)){

?>

<tr>

<td><?= $row['id_produk']; ?></td>
<td><?= $row['kode_produk']; ?></td>
<td><?= $row['nama_produk']; ?></td>
<td><?= $row['kategori']; ?></td>
<td><?= number_format($row['harga']); ?></td>

<td>

<a href="?edit=<?= $row['id_produk']; ?>">
Edit
</a>

|

<a
href="?hapus=<?= $row['id_produk']; ?>"
onclick="return confirm('Yakin hapus data?')"
>
Hapus
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>