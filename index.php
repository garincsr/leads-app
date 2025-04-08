<?php
session_start();
include "service/database.php";

if (isset($_SESSION['simpan_message'])) {
    unset($_SESSION['simpan_message']);
}

$produk = "SELECT * FROM produk";
$result_produk = $db->query($produk);

$sales = "SELECT * FROM sales";
$result_sales = $db->query($sales);

if (isset($_POST["simpan"])) {
    $tanggal = $_POST["tanggal"];
    $produk = $_POST["produk"];
    $sales = $_POST["sales"];
    $whatsapp = $_POST["whatsapp"];
    $lead = $_POST["lead"];
    $kota = $_POST["kota"];

    $query = "INSERT INTO leads (tanggal, id_sales, id_produk, no_wa, nama_lead, kota) 
                VALUES ('$tanggal', '$sales', '$produk', '$whatsapp', '$lead', '$kota')";

    try {
        $result = $db->query($query);
        
        if ($result) {
            $_SESSION['simpan_message'] = "Data Berhasil Disimpan";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['simpan_message'] = "Data Gagal Disimpan: " . $db->error;
        }
    } catch (mysqli_sql_exception $e) {
        $_SESSION['simpan_message'] = "Ada kesalahan input: " . $e->getMessage();
    }
}

$simpan_message = $_SESSION['simpan_message'] ?? '';
unset($_SESSION['simpan_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <title>Sistem Leads</title>
</head>
<body>
    <div class="container-fluid vh-100" style="background-color: #E6F0FF">
        <h1 class="mb-5">Selamat Datang Di Tambah Leads</h1>
        <div class="container px-0 border rounded-3 border-black bg-white">
            <?php
                include "layout/header.html";
            ?>

            <form class="w-100" action="index.php" method="POST">
                <i class="text-center"><?= $simpan_message ?></i>
                <div class="d-flex justify-content-between gap-5 mt-1 p-5">
                    <div class="w-50">
                        <div class="d-flex flex-column mb-4">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" />
                        </div>
                        <div class="d-flex flex-column">
                            <label for="produk">Produk</label>
                            <div class="input-group">
                                <select class="form-select" name="produk" aria-label="Pilih Produk">
                                    <option disabled selected>-- Pilih Produk --</option>
                                    <?php while($row = $result_produk->fetch_assoc()): ?>
                                        <option value="<?= $row['id_produk'] ?>"><?= $row['nama_produk'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-50">
                        <div class="d-flex flex-column mb-4">
                            <label for="produk">Sales</label>
                            <div class="input-group">
                                <select class="form-select" name="sales" aria-label="Pilih Sales">
                                    <option disabled selected>-- Pilih Sales --</option>
                                    <?php while($row = $result_sales->fetch_assoc()): ?>
                                        <option value="<?= $row['id_sales'] ?>"><?= $row['nama_sales'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <label for="whatsapp">No. Whatsapp</label>
                            <input type="text" name="whatsapp" placeholder="No. Whatsapp" />
                        </div>
                    </div>

                    <div class="w-50">
                        <div class="d-flex flex-column mb-4">
                            <label for="lead">Nama Lead</label>
                            <input type="text" name="lead" placeholder="Nama Lead" />
                        </div>
                        <div class="d-flex flex-column">
                            <label for="kota">Kota</label>
                            <input type="text" name="kota" placeholder="Kota" />
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mb-4">
                    <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                    <a href="simpan.php" class="btn btn-primary">Lihat Data Leads</a>
                    <button class="btn btn-outline-danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>