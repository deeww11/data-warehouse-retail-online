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

</head>

<body>

<div class="container mt-4">

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

    <hr>


    <table class="table table-bordered table-striped">

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

    <!-- Pagination -->

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
