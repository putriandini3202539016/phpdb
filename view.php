<?php
include_once("config.php");

if (!isset($_GET['no'])) {
    header("Location: index.php");
    exit;
}

$no = intval($_GET['no']);
$stmt = mysqli_prepare($mysqli, "SELECT * FROM mahasiswati WHERE no = ?");
mysqli_stmt_bind_param($stmt, "i", $no);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);

if (!$data) {
    echo "Data tidak ditemukan. <a href='index.php'>Kembali</a>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background:#eaf6ff; font-family:'Inter',sans-serif; }
        .profile-card { max-width:680px; margin:60px auto; background:#fff; padding:26px; border-radius:12px; box-shadow:0 10px 30px rgba(17,82,135,0.06); }
        .gender-icon { font-size:48px; padding:18px; border-radius:12px; background: #f1fbff; color: #0d6efd; }
        .field-label { color:#6b7785; font-weight:600; font-size:13px; }
    </style>
</head>
<body>
<div class="container">
    <div class="profile-card">
        <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex gap-3">
                <div class="gender-icon d-flex align-items-center justify-content-center">
                    <?php if ($data['jeniskelamin'] === 'laki-laki'): ?>
                        <i class="bi bi-gender-male"></i>
                    <?php else: ?>
                        <i class="bi bi-gender-female"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <h4 class="mb-1"><?php echo htmlspecialchars($data['nama']); ?></h4>
                    <div class="text-muted small">NIM: <?php echo htmlspecialchars($data['nim']); ?></div>
                </div>
            </div>

            <div class="text-end">
                <a href="edit.php?no=<?php echo urlencode($data['no']); ?>" class="btn btn-sm btn-warning me-2"><i class="bi bi-pencil-square"></i> Edit</a>
                <a href="index.php" class="btn btn-sm btn-outline-secondary">Kembali</a>
            </div>
        </div>

        <hr>

        <div class="row mt-3">
            <div class="col-md-6 mb-3">
                <div class="field-label">Umur</div>
                <div><?php echo htmlspecialchars($data['umur']); ?> tahun</div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="field-label">Jenis Kelamin</div>
                <div><?php echo htmlspecialchars($data['jeniskelamin']); ?></div>
            </div>
            <div class="col-12">
                <div class="field-label">Sekolah Asal</div>
                <div><?php echo htmlspecialchars($data['asalsekolah']); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
