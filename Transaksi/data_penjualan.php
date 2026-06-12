<?php

include_once "../Config/koneksi.php";


function rupiah($angka)
{
    return 'Rp ' . number_format(
        $angka,
        0,
        ',',
        '.'
    );
}

$produk = [];
$pelanggan = [];
$waktu = [];

$resultProduk = mysqli_query(
    $conn,
    "SELECT *
     FROM dim_produk
     ORDER BY nama_produk ASC"
);

while ($row = mysqli_fetch_assoc($resultProduk)) {
    $produk[] = $row;
}

$resultPelanggan = mysqli_query(
    $conn,
    "SELECT *
     FROM dim_pelanggan
     ORDER BY nama_pelanggan ASC"
);

while ($row = mysqli_fetch_assoc($resultPelanggan)) {
    $pelanggan[] = $row;
}

$resultWaktu = mysqli_query(
    $conn,
    "SELECT *
     FROM dim_waktu
     ORDER BY tanggal ASC"
);

while ($row = mysqli_fetch_assoc($resultWaktu)) {
    $waktu[] = $row;
}

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
         FROM fact_penjualan"
    )
);

$totalPage = ceil(
    $totalData['total'] / $limit
);

$dataPenjualan = mysqli_query(
    $conn,
    "SELECT
        fp.id_penjualan,
        fp.id_produk,
        fp.id_pelanggan,
        fp.id_waktu,
        dp.nama_produk,
        dpl.nama_pelanggan,
        dw.tanggal,
        fp.jumlah,
        fp.harga_satuan,
        fp.total_harga
    FROM fact_penjualan fp
    JOIN dim_produk dp
        ON fp.id_produk = dp.id_produk
    JOIN dim_pelanggan dpl
        ON fp.id_pelanggan = dpl.id_pelanggan
    JOIN dim_waktu dw
        ON fp.id_waktu = dw.id_waktu
    ORDER BY fp.id_penjualan DESC
    LIMIT $start, $limit"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Penjualan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body{
    background:#F5F1E8;
    color:#4A3428;
    min-height:100vh;
}

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
    margin:0;
    padding:0;
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

.container{
    width:95% !important;
    max-width:none !important;
    margin:30px auto;
}

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

.modal-footer{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    background:#FFF8E7;
    border-top:1px solid #EADBC8;
}

.modal-footer .btn-success{
    margin:0;
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
        <li><a href="../Master Data/data_produk.php">Produk</a></li>
        <li><a href="../Master Data/data_waktu.php">Waktu</a></li>
        <li><a href="../Master Data/data_pelanggan.php">Pelanggan</a></li>
        <li><a href="data_penjualan.php" class="active">Penjualan</a></li>
    </ul>

</div>

<div class="container">

    <div class="card">

        <h2>Data Penjualan</h2>

        <form
            action="../controllers/PenjualanController.php"
            method="POST">

            <div class="mb-3">

                <label class="form-label">
                    Produk
                </label>

                <select
                    id="produk"
                    name="id_produk"
                    class="form-control"
                    required>

                    <option value="">
                        Pilih Produk
                    </option>

                    <?php foreach ($produk as $p) : ?>

                        <option
                            value="<?= $p['id_produk']; ?>"
                            data-harga="<?= $p['harga']; ?>">

                            <?= $p['nama_produk']; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Pelanggan
                </label>

                <select
                    name="id_pelanggan"
                    class="form-control"
                    required>

                    <option value="">
                        Pilih Pelanggan
                    </option>

                    <?php foreach ($pelanggan as $pl) : ?>

                        <option
                            value="<?= $pl['id_pelanggan']; ?>">

                            <?= $pl['nama_pelanggan']; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Tanggal
                </label>

                <select
                    name="id_waktu"
                    class="form-control"
                    required>

                    <option value="">
                        Pilih Tanggal
                    </option>

                    <?php foreach ($waktu as $w) : ?>

                        <option
                            value="<?= $w['id_waktu']; ?>">

                            <?= $w['tanggal']; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Jumlah
                </label>

                <input
                    type="number"
                    id="jumlah"
                    name="jumlah"
                    class="form-control"
                    min="1"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Harga Satuan
                </label>

                <input
                    type="text"
                    id="harga_satuan"
                    class="form-control"
                    readonly>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Total Harga
                </label>

                <input
                    type="text"
                    id="total_harga"
                    class="form-control"
                    readonly>

            </div>

            <button
                type="submit"
                name="simpan"
                class="btn btn-primary">

                Simpan

            </button>

        </form>

    </div>


<div class="card">

    <h3>Daftar Data Penjualan</h3>

    <table>

        <tr>

            <th>No</th>
            <th>Produk</th>
            <th>Pelanggan</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Total Harga</th>
            <th>Aksi</th>

        </tr>

        <?php while ($row = mysqli_fetch_assoc($dataPenjualan)) : ?>

            <tr>

                <td><?= $no++; ?></td>

                <td><?= $row['nama_produk']; ?></td>

                <td><?= $row['nama_pelanggan']; ?></td>

                <td><?= $row['tanggal']; ?></td>

                <td><?= $row['jumlah']; ?></td>

                <td><?= rupiah($row['harga_satuan']); ?></td>

                <td><?= rupiah($row['total_harga']); ?></td>

                <td>

                    <button
                        type="button"
                        class="btn-edit"

                        data-id="<?= $row['id_penjualan']; ?>"
                        data-produk="<?= $row['id_produk']; ?>"
                        data-pelanggan="<?= $row['id_pelanggan']; ?>"
                        data-waktu="<?= $row['id_waktu']; ?>"
                        data-jumlah="<?= $row['jumlah']; ?>"

                        data-bs-toggle="modal"
                        data-bs-target="#modalEdit">

                        Edit

                    </button>

                    <a
                        href="../controllers/PenjualanController.php?hapus=<?= $row['id_penjualan']; ?>"
                        class="btn-hapus">

                        Hapus

                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

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
    id="modalEdit"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form
                action="../controllers/PenjualanController.php"
                method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Data Penjualan
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
                        name="id_penjualan"
                        id="edit_id_penjualan">

                    <label class="form-label">
                        Produk
                    </label>

                    <select
                        name="id_produk"
                        id="edit_produk"
                        class="form-control"
                        required>

                        <?php foreach ($produk as $p) : ?>

                            <option
                                value="<?= $p['id_produk']; ?>"
                                data-harga="<?= $p['harga']; ?>">

                                <?= $p['nama_produk']; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <br>

                    <label class="form-label">
                        Pelanggan
                    </label>

                    <select
                        name="id_pelanggan"
                        id="edit_pelanggan"
                        class="form-control"
                        required>

                        <?php foreach ($pelanggan as $pl) : ?>

                            <option
                                value="<?= $pl['id_pelanggan']; ?>">

                                <?= $pl['nama_pelanggan']; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <br>

                    <label class="form-label">
                        Tanggal
                    </label>

                    <select
                        name="id_waktu"
                        id="edit_waktu"
                        class="form-control"
                        required>

                        <?php foreach ($waktu as $w) : ?>

                            <option
                                value="<?= $w['id_waktu']; ?>">

                                <?= $w['tanggal']; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <br>

                    <label class="form-label">
                        Jumlah
                    </label>

                    <input
                        type="number"
                        name="jumlah"
                        id="edit_jumlah"
                        class="form-control"
                        min="1"
                        required>

                    <br>

                    <label class="form-label">
                        Harga Satuan
                    </label>

                    <input
                        type="text"
                        id="edit_harga"
                        class="form-control"
                        readonly>

                    <br>

                    <label class="form-label">
                        Total Harga
                    </label>

                    <input
                        type="text"
                        id="edit_total"
                        class="form-control"
                        readonly>

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

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

function hitungTotal(
    produkId,
    jumlahId,
    hargaId,
    totalId
) {

    const produk =
        document.getElementById(produkId);

    const jumlah =
        document.getElementById(jumlahId);

    if (!produk || !jumlah) {
        return;
    }

    const harga =
        produk.options[
            produk.selectedIndex
        ]?.dataset.harga || 0;

    const qty =
        parseInt(jumlah.value) || 0;

    document.getElementById(hargaId).value =
        'Rp ' + Number(harga)
        .toLocaleString('id-ID');

    document.getElementById(totalId).value =
        'Rp ' + (harga * qty)
        .toLocaleString('id-ID');
}

document.getElementById('produk')
.addEventListener('change', function(){

    hitungTotal(
        'produk',
        'jumlah',
        'harga_satuan',
        'total_harga'
    );

});

document.getElementById('jumlah')
.addEventListener('input', function(){

    hitungTotal(
        'produk',
        'jumlah',
        'harga_satuan',
        'total_harga'
    );

});

document.querySelectorAll('.btn-edit')
.forEach(function(btn){

    btn.addEventListener('click', function(){

        document.getElementById(
            'edit_id_penjualan'
        ).value = this.dataset.id;

        document.getElementById(
            'edit_produk'
        ).value = this.dataset.produk;

        document.getElementById(
            'edit_pelanggan'
        ).value = this.dataset.pelanggan;

        document.getElementById(
            'edit_waktu'
        ).value = this.dataset.waktu;

        document.getElementById(
            'edit_jumlah'
        ).value = this.dataset.jumlah;

        hitungTotal(
            'edit_produk',
            'edit_jumlah',
            'edit_harga',
            'edit_total'
        );

    });

});

document.getElementById('edit_produk')
.addEventListener('change', function(){

    hitungTotal(
        'edit_produk',
        'edit_jumlah',
        'edit_harga',
        'edit_total'
    );

});

document.getElementById('edit_jumlah')
.addEventListener('input', function(){

    hitungTotal(
        'edit_produk',
        'edit_jumlah',
        'edit_harga',
        'edit_total'
    );

});

document.querySelectorAll('.btn-hapus')
.forEach(function(button){

    button.addEventListener('click', function(e){

        e.preventDefault();

        const url = this.href;

        Swal.fire({

            title: 'Yakin?',
            text: 'Data penjualan akan dihapus',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'

        }).then((result) => {

            if(result.isConfirmed){
                window.location.href = url;
            }

        });

    });

});

</script>

<?php

$pesan = [

    'simpan' =>
        'Data penjualan berhasil disimpan',

    'update' =>
        'Data penjualan berhasil diperbarui',

    'hapus' =>
        'Data penjualan berhasil dihapus'

];

?>

<?php if(
    isset($_GET['success']) &&
    isset($pesan[$_GET['success']])
) : ?>

<script>

Swal.fire({

    icon: 'success',

    title: 'Berhasil',

    text:
        '<?= $pesan[$_GET['success']] ?>'

});

</script>

<?php endif; ?>

</body>
</html>