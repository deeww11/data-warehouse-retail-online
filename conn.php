<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "retail"
);

if(!$conn){
    die("Koneksi Gagal : " . mysqli_connect_error());
}
?>
