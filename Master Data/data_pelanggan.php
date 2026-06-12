<?php

include_once "../Config/koneksi.php";

/** @var mysqli $conn */

$limit = 25;

$page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;

$start = ($page - 1) * $limit;

$no = $start + 1;

$totalData = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
        FROM dim_pelanggan"
    )
);

$totalPage = ceil(
    $totalData['total'] / $limit
);

$dataPelanggan = mysqli_query(
    $conn,
    "SELECT *
    FROM dim_pelanggan
    ORDER BY id_pelanggan DESC
    LIMIT $start, $limit"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Data Pelanggan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

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
        width:95% !important;
        max-width:none !important;
        margin:30px auto;
    }

    /* =========================
    CARD
    ========================= */

    .card{
        background:#FFF8E7 !important;
        padding:25px !important;
        border-radius:15px !important;
        margin-bottom:25px !important;
        box-shadow:0 4px 15px rgba(0,0,0,0.08) !important;
        border:none !important;
    }

    .card h2,
    .card h3{
        color:#6F4E37;
        margin-bottom:20px;
    }

    .card h2{
        font-size:24px;
        font-weight:700;
    }

    .card h3{
        font-size:22px;
        font-weight:700;
    }

    /* =========================
    FORM
    ========================= */

    .form-label{
        display:block;
        margin-bottom:8px;
        font-weight:600;
        color:#5C4033;
    }

    .form-control{
        width:100%;
        padding:12px;
        border:1px solid #D2B48C;
        border-radius:8px;
        background:#FDF6EC;
        color:#4A3428;
    }

    .form-control:focus{
        outline:none;
        border:2px solid #A67B5B;
        background:white;
        box-shadow:none;
    }

    /* =========================
    BUTTON
    ========================= */

    .btn-primary,
    .btn-success{
        background:green !important;
        border:none !important;
        color:white;
        padding:12px 20px;
        border-radius:8px;
        font-weight:600;
    }

    .btn-primary:hover,
    .btn-success:hover{
        background:#6F4E37 !important;
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
        color:white !important;
        text-decoration:none;
        padding:8px 12px;
        border-radius:6px;
        border:none;
        transition:0.3s;
        margin-right:8px;
    }

    .btn-edit:hover{
        background:#8B5E3C;
    }

    .btn-hapus{
        background:red;
        color:white !important;
        text-decoration:none;
        padding:8px 12px;
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
    PAGINATION
    ========================= */

    .pagination{
        margin-top:20px;
        display:flex;
        justify-content:center;
        gap:8px;
    }

    .pagination a{
        text-decoration:none;
        padding:10px 15px;
        background:#8B5E3C;
        color:white;
        border-radius:6px;
        transition:0.3s;
    }

    .pagination a:hover{
        background:#6F4E37;
    }

    .pagination .active-page{
        background:#4A3428;
    }

    /* =========================
    RESPONSIVE
    ========================= */

    .modal-content{
    background:#FFF8E7;
    border:none;
    border-radius:15px;
    }

    .modal-header{
        background:#FFF8E7;
        border-bottom:1px solid #EADBC8;
    }

    .modal-title{
        color:#6F4E37;
        font-weight:600;
    }

    .modal-footer{
        display:flex;
        justify-content:flex-end;
        gap:10px;
        background:#FFF8E7;
        border-top:1px solid #EADBC8;
    }

    .btn-batal{
        background:red !important;
        color:white !important;
        border:none !important;
        padding:12px 20px;
        border-radius:8px;
        cursor:pointer;
        transition:0.3s;
        font-weight:600;
    }

    .btn-batal:hover{
        background:#B85C38 !important;
    }

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
        <li><a href="data_produk.php">Produk</a></li>
        <li><a href="data_waktu.php">Waktu</a></li>
        <li><a href="data_pelanggan.php" class="active">Pelanggan</a></li>
        <li><a href="../Transaksi/data_penjualan.php">Penjualan</a></li>
    </ul>

</div>

<div class="container">

    <div class="card">
    <h2>Data Pelanggan</h2>

    <hr>
    <form
        action="../Controllers/PelangganController.php"
        method="POST">

        <div class="mb-3">

            <label class="form-label">
                Kode Pelanggan
            </label>

            <input
                type="text"
                name="kode_pelanggan"
                class="form-control"
                required>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Nama Pelanggan
            </label>

            <input
                type="text"
                name="nama_pelanggan"
                class="form-control"
                required>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Jenis Kelamin
            </label>

            <select
                name="jenis_kelamin"
                class="form-control"
                required>

                <option value="">
                    Pilih
                </option>

                <option value="L">
                    Laki-laki
                </option>

                <option value="P">
                    Perempuan
                </option>

            </select>

        </div>

        <div class="mb-3">

            <label class="form-label">
                Kota
            </label>

            <input
                type="text"
                name="kota"
                class="form-control"
                required>

        </div>

        <button
            type="submit"
            name="simpan"
            class="btn btn-primary">

            Simpan

        </button>

    </form>
    </div>

    <hr>

    <div class="card">
    <h3>Daftar Pelanggan</h3>
    <table>

        <thead>

        <tr>

            <th>No</th>
            <th>Kode Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th>Jenis Kelamin</th>
            <th>Kota</th>
            <th width="150">
                Aksi
            </th>

        </tr>

        </thead>

        <tbody>

        <?php while ($row = mysqli_fetch_assoc($dataPelanggan)) : ?>

            <tr>

                <td><?= $no++; ?></td>

                <td><?= $row['kode_pelanggan']; ?></td>

                <td><?= $row['nama_pelanggan']; ?></td>

                <td><?= $row['jenis_kelamin']; ?></td>

                <td><?= $row['kota']; ?></td>

                <td>

                    <button
                        class="btn btn-warning btn-sm btn-edit"

                        data-id="<?= $row['id_pelanggan']; ?>"
                        data-kode="<?= $row['kode_pelanggan']; ?>"
                        data-nama="<?= $row['nama_pelanggan']; ?>"
                        data-jk="<?= $row['jenis_kelamin']; ?>"
                        data-kota="<?= $row['kota']; ?>"

                        data-bs-toggle="modal"
                        data-bs-target="#modalEdit">

                        Edit

                    </button>

                    <a
                        href="../Controllers/PelangganController.php?hapus=<?= $row['id_pelanggan']; ?>"
                        class="btn btn-danger btn-sm btn-hapus">

                        Hapus

                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

    <div class="pagination">

    <?php if($page > 1){ ?>
        <a href="?page=<?= $page-1 ?>">
            ← Previous
        </a>
    <?php } ?>

    <?php for($i=1; $i<=$totalPage; $i++){ ?>
        <a
            href="?page=<?= $i ?>"
            class="<?= ($i==$page) ? 'active-page' : '' ?>">
            <?= $i ?>
        </a>
    <?php } ?>

    <?php if($page < $totalPage){ ?>
        <a href="?page=<?= $page+1 ?>">
            Next →
        </a>
    <?php } ?>

    </div>

</div>


<div
    class="modal fade"
    id="modalEdit">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="../Controllers/PelangganController.php"
                method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Edit Pelanggan

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="id_pelanggan"
                        id="edit_id">

                    <div class="mb-3">

                        <label>Kode Pelanggan</label>

                        <input
                            type="text"
                            name="kode_pelanggan"
                            id="edit_kode"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Nama Pelanggan</label>

                        <input
                            type="text"
                            name="nama_pelanggan"
                            id="edit_nama"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Jenis Kelamin</label>

                        <select
                            name="jenis_kelamin"
                            id="edit_jk"
                            class="form-control">

                            <option value="L">
                                Laki-laki
                            </option>

                            <option value="P">
                                Perempuan
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Kota</label>

                        <input
                            type="text"
                            name="kota"
                            id="edit_kota"
                            class="form-control"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn-batal"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        name="update"
                        class="btn btn-success">

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.querySelectorAll('.btn-edit')
.forEach(function(btn){

    btn.addEventListener('click', function(){

        document.getElementById('edit_id').value =
            this.dataset.id;

        document.getElementById('edit_kode').value =
            this.dataset.kode;

        document.getElementById('edit_nama').value =
            this.dataset.nama;

        document.getElementById('edit_jk').value =
            this.dataset.jk;

        document.getElementById('edit_kota').value =
            this.dataset.kota;

    });

});

document.querySelectorAll('.btn-hapus')
.forEach(function(button){

    button.addEventListener('click', function(e){

        e.preventDefault();

        const url = this.href;

        Swal.fire({

            title: 'Yakin?',
            text: 'Data pelanggan akan dihapus',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'

        }).then((result) => {

            if (result.isConfirmed) {

                window.location.href = url;

            }

        });

    });

});

</script>

<?php

if (isset($_GET['success'])) {

    $pesan = [

        'simpan' => 'Data pelanggan berhasil disimpan',

        'update' => 'Data pelanggan berhasil diperbarui',

        'hapus' => 'Data pelanggan berhasil dihapus'

    ];

    $tipe = $_GET['success'];

    if (isset($pesan[$tipe])) {

?>

<script>

Swal.fire({

    title: 'Berhasil',

    text: '<?= $pesan[$tipe] ?>',

    icon: 'success',

    timer: 2000,

    showConfirmButton: false

});

</script>

<?php

    }

}

?>

</body>
</html>
