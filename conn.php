<?php
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "retail"
);

if(!$conn){
    die("Koneksi gagal");
}
?>