<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database_name = "sistem_leads";

    $db = mysqli_connect($hostname, $username, $password, $database_name);

    if($db->connect_error) {
        echo"Koneksi ke database gagal";
        die("Error!");
    }
?>