<?php
include_once("config.php");

// proses pencarian
$search = "";
if (isset($_GET['cari'])) {
    $search = trim($_GET['cari']);
    $stmt = mysqli_prepare($mysqli, "SELECT * FROM mahasiswati WHERE nama LIKE CONCAT('%',?,'%') OR nim LIKE CONCAT('%',?,'%') ORDER BY no DESC");
    mysqli_stmt_bind_param($stmt, "ss", $search, $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($mysqli, "SELECT * FROM mahasiswati ORDER BY no DESC");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Mahasiswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root{
            --pastel-blue:#d7e9f7;
            --pastel-blue-2:#bfe0f7;
            --accent:#5aa6df;
            --accent-2:#3e8bc8;
        }
        body { background: var(--pastel-blue); font-family: 'Inter', sans-serif; }
        .card-app { border-radius: 12px; box-shadow: 0 6px 20px rgba(17, 82, 135, 0.08); }
        .brand { color: var(--accent); font-size: 42px; }
        .table thead th { background: var(--pastel-blue-2); border: 0; }
        .btn-info { background: var(--accent); border: none; color: #fff; }
        .btn-info:hover { background: var(--accent-2); }
        .search-input { max-width: 480px; }
        .badge-gender { font-weight:600; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card card-app p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-mortarboard-fill brand me-3"></i>
                <div>
                    <h3 class="mb-0">Daftar Mahasiswa</h3>
                    <small class="text-muted">Data mahasiswa — warna biru pastel</small>
                </div>
            </div>
            <div>
                <a href="add.php" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Tambah Mahasiswa
                </a>
            </div>
        </div>

        <!-- Search -->
        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="cari" class="form-control me-2 search-input" placeholder="Cari nama / NIM ..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
            <?php if($search!==""): ?>
                <a href="index.php" class="btn btn-link ms-2">Reset</a>
            <?php endif; ?>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Umur</th>
                        <th>Jenis Kelamin</th>
                        <th>Sekolah Asal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (!$result || mysqli_num_rows($result) == 0) {
                    echo '<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td></tr>';
                } else {
                    while ($user = mysqli_fetch_assoc($result)) {
                        // gender badge & icon
                        if ($user['jeniskelamin'] === 'laki-laki') {
                            $genderHtml = "<span class='badge bg-primary badge-gender'><i class='bi bi-gender-male me-1'></i> Laki-Laki</span>";
                        } else {
                            $genderHtml = "<span class='badge bg-pink text-white badge-gender' style='background:#ff9fc3;'><i class='bi bi-gender-female me-1'></i> Perempuan</span>";
                        }
                        echo "<tr>";
                        echo "<td>".htmlspecialchars($user['nim'])."</td>";
                        echo "<td>".htmlspecialchars($user['nama'])."</td>";
                        echo "<td>".htmlspecialchars($user['umur'])."</td>";
                        echo "<td>$genderHtml</td>";
                        echo "<td>".htmlspecialchars($user['asalsekolah'])."</td>";
                        echo "<td>
                                <a href='view.php?no=".urlencode($user['no'])."' class='btn btn-info btn-sm me-1'>Lihat</a>
                                <a href='edit.php?no=".urlencode($user['no'])."' class='btn btn-warning btn-sm me-1'><i class='bi bi-pencil-square'></i></a>
                                <a href='delete.php?no=".urlencode($user['no'])."' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin menghapus data ini?');\"><i class='bi bi-trash'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
