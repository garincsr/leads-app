<?php
session_start();
include "service/database.php";

if (!isset($_GET['id'])) {
    header("Location: tampil_leads.php");
    exit();
}

$id_leads = $_GET['id'];
$query = "SELECT * FROM leads WHERE id_leads = $id_leads";
$result = $db->query($query);
$data = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $tanggal = $_POST['tanggal'];
    $produk = $_POST['produk'];
    $sales = $_POST['sales'];
    $whatsapp = $_POST['whatsapp'];
    $lead = $_POST['lead'];
    $kota = $_POST['kota'];

    $update_query = "UPDATE leads SET 
                    tanggal = '$tanggal',
                    id_produk = '$produk',
                    id_sales = '$sales',
                    no_wa = '$whatsapp',
                    nama_lead = '$lead',
                    kota = '$kota'
                    WHERE id_leads = $id_leads";

    if ($db->query($update_query)) {
        $_SESSION['update_message'] = "Data dengan ID " . str_pad($id_leads, 3, '0', STR_PAD_LEFT) . " berhasil diupdate!";
        header("Location: tampil_leads.php");
        exit();
    } else {
        $error = "Gagal update data: " . $db->error;
    }
}

$produk_options = $db->query("SELECT * FROM produk");
$sales_options = $db->query("SELECT * FROM sales");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Data Leads</title>
</head>
<body>
    <div class="container mt-5 border rounded-3 border-black px-0">
        <?php
            include "layout/header.html";
        ?>

        <div class="p-4">
            <h2 class="mb-4">Edit Data Leads</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Produk</label>
                        <select name="produk" class="form-select" required>
                            <?php while($row = $produk_options->fetch_assoc()): ?>
                                <option value="<?= $row['id_produk'] ?>" <?= $row['id_produk'] == $data['id_produk'] ? 'selected' : '' ?>>
                                    <?= $row['nama_produk'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sales</label>
                        <select name="sales" class="form-select" required>
                            <?php while($row = $sales_options->fetch_assoc()): ?>
                                <option value="<?= $row['id_sales'] ?>" <?= $row['id_sales'] == $data['id_sales'] ? 'selected' : '' ?>>
                                    <?= $row['nama_sales'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Nama Lead</label>
                        <input type="text" name="lead" class="form-control" value="<?= $data['nama_lead'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">No. Whatsapp</label>
                        <input type="text" name="whatsapp" class="form-control" value="<?= $data['no_wa'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kota</label>
                        <input type="text" name="kota" class="form-control" value="<?= $data['kota'] ?>" required>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="tampil_leads.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" name="update" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>