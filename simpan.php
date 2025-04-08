<?php
session_start();
include "service/database.php";

if (isset($_SESSION['update_message'])) {
    $update_message = $_SESSION['update_message'];
    unset($_SESSION['update_message']);
}

$current_month = date('m');
$query = "SELECT l.id_leads, l.tanggal, s.nama_sales, p.nama_produk, l.nama_lead, l.no_wa, l.kota 
          FROM leads l
          JOIN produk p ON l.id_produk = p.id_produk
          JOIN sales s ON l.id_sales = s.id_sales
          WHERE MONTH(l.tanggal) = '$current_month'";

// Fitur pencarian produk
if (isset($_GET['search_produk']) && !empty($_GET['search_produk'])) {
    $produk_id = $_GET['search_produk'];
    $query = "SELECT l.id_leads, l.tanggal, s.nama_sales, p.nama_produk, l.nama_lead, l.no_wa, l.kota 
              FROM leads l
              JOIN produk p ON l.id_produk = p.id_produk
              JOIN sales s ON l.id_sales = s.id_sales
              WHERE l.id_produk = '$produk_id'";
}

// Fitur pencarian sales dan bulan
if (isset($_GET['search_sales']) && !empty($_GET['search_sales']) && 
    isset($_GET['search_bulan']) && !empty($_GET['search_bulan'])) {
    $sales_id = $_GET['search_sales'];
    $bulan = $_GET['search_bulan'];
    $query = "SELECT l.id_leads, l.tanggal, s.nama_sales, p.nama_produk, l.nama_lead, l.no_wa, l.kota 
              FROM leads l
              JOIN produk p ON l.id_produk = p.id_produk
              JOIN sales s ON l.id_sales = s.id_sales
              WHERE l.id_sales = '$sales_id' AND MONTH(l.tanggal) = '$bulan'";
}

$result_leads = $db->query($query);
$produk_options = $db->query("SELECT * FROM produk");
$sales_options = $db->query("SELECT * FROM sales");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Data Leads</title>
</head>
<body>
    <div class="container mt-5 border rounded-3 border-black px-0">
        <?php
            include "layout/header.html";
        ?>

        <div class="p-4">
            <h2 class="mb-4">Data Leads</h2>
            
            <?php if (!empty($update_message)): ?>
                <div class="alert alert-success"><?= $update_message ?></div>
            <?php endif; ?>

            <!-- Form Pencarian Produk -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Cari Berdasarkan Produk</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="simpan.php">
                        <div class="row">
                            <div class="col-md-8">
                                <select name="search_produk" class="form-select">
                                    <option value="">-- Pilih Produk --</option>
                                    <?php while($row = $produk_options->fetch_assoc()): ?>
                                        <option value="<?= $row['id_produk'] ?>"><?= $row['nama_produk'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Cari</button>
                                <a href="simpan.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form Pencarian Sales dan Bulan -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5>Cari Berdasarkan Sales dan Bulan</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="simpan.php">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="search_sales" class="form-select">
                                    <option value="">-- Pilih Sales --</option>
                                    <?php while($row = $sales_options->fetch_assoc()): ?>
                                        <option value="<?= $row['id_sales'] ?>"><?= $row['nama_sales'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="search_bulan" class="form-select">
                                    <option value="">-- Pilih Bulan --</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info">Cari</button>
                                <a href="simpan.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Data Leads -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>ID Input</th>
                            <th>Tanggal</th>
                            <th>Sales</th>
                            <th>Produk</th>
                            <th>Nama Leads</th>
                            <th>No WA</th>
                            <th>Kota</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_leads->num_rows > 0): ?>
                            <?php $no = 1; ?>
                            <?php while($row = $result_leads->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= str_pad($row['id_leads'], 3, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                    <td><?= $row['nama_sales'] ?></td>
                                    <td><?= $row['nama_produk'] ?></td>
                                    <td><?= $row['nama_lead'] ?></td>
                                    <td><?= substr($row['no_wa'], 0, 4) . 'xxxx' ?></td>
                                    <td><?= $row['kota'] ?></td>
                                    <td>
                                        <a href="edit_leads.php?id=<?= $row['id_leads'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>