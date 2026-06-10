
<?php

include_once "../Config/koneksi.php";

$produk = mysqli_query(
    $conn,
    "SELECT * FROM dim_produk ORDER BY nama_produk ASC"
);

$pelanggan = mysqli_query(
    $conn,
    "SELECT * FROM dim_pelanggan ORDER BY nama_pelanggan ASC"
);

$waktu = mysqli_query(
    $conn,
    "SELECT * FROM dim_waktu ORDER BY tanggal ASC"
);

$limit = 25;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$start = ($page - 1) * $limit;

$totalData = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) as total
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-4">

    <h2>Data Penjualan</h2>

    <hr>

    <form
        action="../controllers/PenjualanController.php"
        method="POST">

        <div class="mb-3">

            <label>Produk</label>

            <select
                name="id_produk"
                class="form-control"
                required>

                <option value="">Pilih Produk</option>

                <?php while($p = mysqli_fetch_assoc($produk)) : ?>

                    <option
                        value="<?= $p['id_produk']; ?>"
                        data-harga="<?= $p['harga']; ?>">

                        <?= $p['nama_produk']; ?>

                    </option>

                <?php endwhile; ?>

            </select>

        </div>

        <div class="mb-3">

            <label>Pelanggan</label>

            <select
                name="id_pelanggan"
                class="form-control"
                required>

                <option value="">Pilih Pelanggan</option>

                <?php while($pl = mysqli_fetch_assoc($pelanggan)) : ?>

                    <option value="<?= $pl['id_pelanggan']; ?>">

                        <?= $pl['nama_pelanggan']; ?>

                    </option>

                <?php endwhile; ?>

            </select>

        </div>

        <div class="mb-3">

            <label>Tanggal</label>

            <select
                name="id_waktu"
                class="form-control"
                required>

                <option value="">Pilih Tanggal</option>

                <?php while($w = mysqli_fetch_assoc($waktu)) : ?>

                    <option value="<?= $w['id_waktu']; ?>">

                        <?= $w['tanggal']; ?>

                    </option>

                <?php endwhile; ?>

            </select>

        </div>

        <div class="mb-3">

            <label>Jumlah</label>

            <input
                type="number"
                name="jumlah"
                class="form-control"
                min="1"
                required>

        </div>

        <div class="mb-3">

            <label>Harga Satuan</label>

            <input
                type="text"
                id="harga_satuan"
                class="form-control"
                readonly>

        </div>

        <div class="mb-3">

            <label>Total Harga</label>

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

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>ID</th>
                <th>Produk</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

            <?php while($row = mysqli_fetch_assoc($dataPenjualan)) : ?>

                <tr>

                    <td><?= $row['id_penjualan']; ?></td>

                    <td><?= $row['nama_produk']; ?></td>

                    <td><?= $row['nama_pelanggan']; ?></td>

                    <td><?= $row['tanggal']; ?></td>

                    <td><?= $row['jumlah']; ?></td>

                    <td><?= number_format($row['harga_satuan']); ?></td>

                    <td><?= number_format($row['total_harga']); ?></td>

                    <td>

                        <a
                            href="?edit=<?= $row['id_penjualan']; ?>"
                            class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <a
                            href="../controllers/PenjualanController.php?hapus=<?= $row['id_penjualan']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus data?')">

                            Hapus

                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

    <nav>

        <ul class="pagination">

            <?php for($i = 1; $i <= $totalPage; $i++) : ?>

                <li class="page-item">

                    <a
                        class="page-link"
                        href="?page=<?= $i ?>">

                        <?= $i ?>

                    </a>

                </li>

            <?php endfor; ?>

        </ul>

    </nav>

</div>

<script>

const produk = document.querySelector('select[name="id_produk"]');
const jumlah = document.querySelector('input[name="jumlah"]');

const hargaSatuan = document.getElementById('harga_satuan');
const totalHarga = document.getElementById('total_harga');

function hitungTotal() {

    let selected =
        produk.options[produk.selectedIndex];

    let harga =
        selected.getAttribute('data-harga') || 0;

    let qty =
        parseInt(jumlah.value) || 0;

    hargaSatuan.value =
        Number(harga).toLocaleString('id-ID');

    totalHarga.value =
        (harga * qty).toLocaleString('id-ID');
}

produk.addEventListener(
    'change',
    hitungTotal
);

jumlah.addEventListener(
    'input',
    hitungTotal
);

</script>

</body>
</html>