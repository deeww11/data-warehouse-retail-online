<?php
include "../Config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Waktu</title>

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
}

.container{
    width:95%;
    margin:30px auto;
}

.card{
    background:#FFF8E7;
    padding:25px;
    border-radius:15px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.card h2{
    color:#6F4E37;
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#5C4033;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:0;
    border:1px solid #D2B48C;
    border-radius:8px;
    background:#FDF6EC;
    color:#4A3428;
    height:50px;
}

input:focus{
    outline:none;
    border:2px solid #A67B5B;
    background:white;
}

button{
    background:green;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

button:hover{
    background:#6F4E37;
}

.button-col button{
    width:100%;
    height:50px;
}


.row{
    display:flex;
    gap:15px;
    align-items:flex-end;
}

.col{
    flex:1;
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

    .row{
        flex-direction:column;
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
        <li><a href="data_waktu.php" class="active">Waktu</a></li>
        <li><a href="data_pelanggan.php">Pelanggan</a></li>
        <li><a href="../Transaksi/data_penjualan.php">Penjualan</a></li>
    </ul>

</div>

<div class="container">

    <div class="card">

        <h2>Generate Data Waktu</h2>

        <form
            action="../Controllers/WaktuController.php"
            method="POST">

            <div class="row">

                <div class="col">

                    <label>Tanggal Awal</label>

                    <input
                        type="date"
                        name="tanggal_awal"
                        required>

                </div>

                <div class="col">

                    <label>Tanggal Akhir</label>

                    <input
                        type="date"
                        name="tanggal_akhir"
                        required>

                </div>

                <div class="col button-col">

                    <button
                        type="submit"
                        name="generate">

                        Generate Data

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_GET['success'])) : ?>

<script>

<?php if($_GET['success'] == 'generate') : ?>

Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Data waktu berhasil digenerate'
});

<?php endif; ?>

</script>

<?php endif; ?>

</body>
</html>