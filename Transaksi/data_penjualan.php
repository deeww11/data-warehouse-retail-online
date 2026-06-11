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

</head>

<body>

<div class="container mt-4">

    <h2>Data Penjualan</h2>

    <hr>

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

    <hr>

<table class="table table-bordered table-striped">

    <thead>

        <tr>

            <th>No</th>
            <th>Produk</th>
            <th>Pelanggan</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Total Harga</th>
            <th width="140">Aksi</th>

        </tr>

    </thead>

    <tbody>

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
                        class="btn btn-warning btn-sm btn-edit"

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
                        class="btn btn-danger btn-sm btn-hapus">

                        Hapus

                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

    </tbody>

</table>

<nav>

    <ul class="pagination">

        <?php for ($i = 1; $i <= $totalPage; $i++) : ?>

            <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">

                <a
                    class="page-link"
                    href="?page=<?= $i; ?>">

                    <?= $i; ?>

                </a>

            </li>

        <?php endfor; ?>

    </ul>

</nav>

</div>

<div
    class="modal fade"
    id="modalEdit"
    tabindex="-1">

    <div class="modal-dialog">

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

                    <div class="mb-3">

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

                    </div>

                    <div class="mb-3">

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

                    </div>

                    <div class="mb-3">

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

                    </div>

                    <div class="mb-3">

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

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Harga Satuan

                        </label>

                        <input
                            type="text"
                            id="edit_harga"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Total Harga

                        </label>

                        <input
                            type="text"
                            id="edit_total"
                            class="form-control"
                            readonly>

                    </div>

                </div>

                <div class="modal-footer">

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