<?php
include "../config/koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>

    <title>Dimensi Waktu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-4">

    <h2>Dimensi Waktu</h2>

    <div class="card">

        <div class="card-header">
            Generate Data Waktu
        </div>

        <div class="card-body">

            <form action="../controllers/WaktuController.php" method="POST">

                <div class="row">

                    <div class="col-md-4">

                        <label>Tanggal Awal</label>

                        <input
                            type="date"
                            name="tanggal_awal"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label>Tanggal Akhir</label>

                        <input
                            type="date"
                            name="tanggal_akhir"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label>&nbsp;</label>

                        <button
                            type="submit"
                            name="generate"
                            class="btn btn-primary w-100">

                            Generate

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>