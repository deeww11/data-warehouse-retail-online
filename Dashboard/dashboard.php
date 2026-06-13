<?php
include "../Config/koneksi.php";

$q1 = mysqli_query($conn,"
SELECT p.nama_produk,
       SUM(f.jumlah) AS total_terjual,
       SUM(f.total_harga) AS total_pendapatan
FROM fact_penjualan f
JOIN dim_produk p
ON f.id_produk = p.id_produk
GROUP BY p.nama_produk
");

$produk = [];
$pendapatan = [];

while($row = mysqli_fetch_assoc($q1)){
    $produk[] = $row['nama_produk'];
    $pendapatan[] = $row['total_pendapatan'];
}

$q2 = mysqli_query($conn,"
SELECT
    w.bulan,
    w.bulan_nama,
    SUM(f.total_harga) AS total_pendapatan
FROM fact_penjualan f
JOIN dim_waktu w
ON f.id_waktu = w.id_waktu
GROUP BY w.bulan, w.bulan_nama
ORDER BY w.bulan
");

$bulan = [];
$pendapatanBulanan = [];

while($row = mysqli_fetch_assoc($q2)){
    $bulan[] = $row['bulan_nama'];
    $pendapatanBulanan[] = $row['total_pendapatan'];
}

$q3 = mysqli_query($conn,"
SELECT p.nama_pelanggan,
       SUM(f.total_harga) AS total_belanja,
       COUNT(f.id_penjualan) AS jumlah_transaksi
FROM fact_penjualan f
JOIN dim_pelanggan p
ON f.id_pelanggan = p.id_pelanggan
GROUP BY p.nama_pelanggan
ORDER BY total_belanja DESC
LIMIT 10
");

$pelanggan = [];
$totalBelanja = [];

while($row = mysqli_fetch_assoc($q3)){
    $pelanggan[] = $row['nama_pelanggan'];
    $totalBelanja[] = $row['total_belanja'];
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Retail</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#F5F1E8;
    color:#4A3428;
}

.navbar{
    background:#6F4E37;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 30px;
}

.logo{
    color:#FFF8E7;
    font-size:22px;
    font-weight:bold;
}

.navbar ul{
    list-style:none;
    display:flex;
    gap:12px;
}

.navbar ul li a{
    text-decoration:none;
    color:white;
    padding:10px 15px;
    border-radius:8px;
}

.navbar ul li a:hover,
.active{
    background:#A67B5B;
}

.container{
    width:95%;
    margin:30px auto;
}

.page-title{
    margin-bottom:20px;
    color:#6F4E37;
}

.card{
    background:#FFF8E7;
    padding:25px;
    border-radius:15px;
    margin-bottom:25px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.card h3{
    margin-bottom:20px;
    color:#6F4E37;
}

.summary{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:25px;
}

.summary-card{
    background:#8B5E3C;
    color:white;
    padding:25px;
    border-radius:12px;
    text-align:center;
}

.summary-card h2{
    font-size:28px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#8B5E3C;
    color:white;
    padding:12px;
}

table td{
    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

table tr:nth-child(even){
    background:#FAF6EF;
}

@media(max-width:768px){

.summary{
    grid-template-columns:1fr;
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
<li><a href="Dashboard/dashboard.php" class="active">Dashboard</a></li>
<li><a href="Master Data/data_produk.php">Produk</a></li>
<li><a href="Master Data/data_waktu.php">Waktu</a></li>
<li><a href="Master Data/data_pelanggan.php">Pelanggan</a></li>
<li><a href="Transaksi/data_penjualan.php">Penjualan</a></li>
</ul>

</div>

<div class="container">

<div class="summary">

<div class="summary-card">
<h4>Total Produk</h4>
<h2><?= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM dim_produk")); ?></h2>
</div>

<div class="summary-card">
<h4>Total Pelanggan</h4>
<h2><?= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM dim_pelanggan")); ?></h2>
</div>

<div class="summary-card">
<h4>Total Transaksi</h4>
<h2><?= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM fact_penjualan")); ?></h2>
</div>

</div>

<div class="card">
<h3>Total Penjualan per Produk</h3>
<canvas id="produkChart"></canvas>
</div>

<div class="card">
<h3>Tren Penjualan Bulanan</h3>
<canvas id="bulanChart"></canvas>
</div>

<div class="card">
<h3>Pelanggan dengan Belanja Tertinggi</h3>
<canvas id="pelangganChart"></canvas>
</div>

</div>

<script>

new Chart(document.getElementById('produkChart'),{

type:'bar',

data:{
labels: <?= json_encode($produk); ?>,
datasets:[{
label:'Pendapatan',
data: <?= json_encode($pendapatan); ?>
}]
}

});

new Chart(document.getElementById('bulanChart'),{

type:'line',

data:{
labels: <?= json_encode($bulan); ?>,
datasets:[{
label:'Pendapatan Bulanan',
data: <?= json_encode($pendapatanBulanan); ?>,
fill:false
}]
}

});

new Chart(document.getElementById('pelangganChart'),{

    type:'bar',

    data:{

        labels: <?= json_encode($pelanggan); ?>,

        datasets:[{

            label:'Total Belanja',

            data: <?= json_encode($totalBelanja); ?>,

            backgroundColor:'#8B5E3C'

        }]
    },

    options:{

        indexAxis:'y',

        responsive:true,

        plugins:{
            legend:{
                display:true
            }
        },

        scales:{
            x:{
                beginAtZero:true
            }
        }
    }

});

</script>

</body>
</html>