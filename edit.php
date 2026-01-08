<?php
include_once("config.php");
$errors = [];

if (isset($_POST['update'])) {
    $no = intval($_POST['no']);
    $nim = trim($_POST['nim']);
    $nama = trim($_POST['nama']);
    $umur = trim($_POST['umur']);
    $jeniskelamin = trim($_POST['jeniskelamin']);
    $asalsekolah = trim($_POST['asalsekolah']);

    if ($nim === '' || $nama === '') $errors[] = "NIM dan Nama wajib diisi.";
    if (!is_numeric($umur) || intval($umur) <= 0) $errors[] = "Umur harus angka positif.";

    if (empty($errors)) {
        $stmt = mysqli_prepare($mysqli, "UPDATE mahasiswati SET nim=?, nama=?, umur=?, jeniskelamin=?, asalsekolah=? WHERE no=?");
        mysqli_stmt_bind_param($stmt, "ssissi", $nim, $nama, $umur, $jeniskelamin, $asalsekolah, $no);
        mysqli_stmt_execute($stmt);
        header("Location: view.php?no=" . urlencode($no));
        exit;
    }
}

// ambil data jika ada param no
if (!isset($_GET['no'])) {
    header("Location: index.php"); exit;
}
$no = intval($_GET['no']);
$stmt = mysqli_prepare($mysqli, "SELECT * FROM mahasiswati WHERE no=?");
mysqli_stmt_bind_param($stmt, "i", $no);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);
if (!$data) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background:#eef9ff; font-family:'Inter',sans-serif; }
        .card-edit { max-width:720px; margin:48px auto; padding:24px; background:#fff; border-radius:12px; box-shadow:0 8px 24px rgba(17,82,135,0.06); }
        .gender-pill { cursor:pointer; border-radius:8px; padding:8px 12px; border:1px solid #e6eef9; }
        .gender-pill.active { background:#5aa6df; color:#fff; border-color:#5aa6df; }
    </style>
</head>
<body>
<div class="container">
    <div class="card-edit">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Mahasiswa</h4>
            <a href="view.php?no=<?php echo urlencode($data['no']); ?>" class="btn btn-link">Batal</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="edit.php" class="row g-3">
            <input type="hidden" name="no" value="<?php echo htmlspecialchars($data['no']); ?>">
            <div class="col-md-6">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" class="form-control" value="<?php echo htmlspecialchars($data['nim']); ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Umur</label>
                <input type="number" min="1" name="umur" class="form-control" value="<?php echo htmlspecialchars($data['umur']); ?>" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">Jenis Kelamin</label>
                <div class="d-flex gap-2">
                    <label class="gender-pill d-flex align-items-center gap-2 px-3 <?php if($data['jeniskelamin']=='laki-laki') echo 'active'; ?>" id="pill-l">
                        <i class="bi bi-gender-male"></i>
                        <input type="radio" name="jeniskelamin" value="laki-laki" class="d-none" <?php if($data['jeniskelamin']=='laki-laki') echo 'checked'; ?>>
                        <span>Laki-Laki</span>
                    </label>

                    <label class="gender-pill d-flex align-items-center gap-2 px-3 <?php if($data['jeniskelamin']=='perempuan') echo 'active'; ?>" id="pill-p">
                        <i class="bi bi-gender-female"></i>
                        <input type="radio" name="jeniskelamin" value="perempuan" class="d-none" <?php if($data['jeniskelamin']=='perempuan') echo 'checked'; ?>>
                        <span>Perempuan</span>
                    </label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Sekolah Asal</label>
                <input type="text" name="asalsekolah" class="form-control" value="<?php echo htmlspecialchars($data['asalsekolah']); ?>">
            </div>

            <div class="col-12 text-end">
                <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.gender-pill').forEach(function(pill){
        pill.addEventListener('click', function(){
            document.querySelectorAll('.gender-pill').forEach(p=>p.classList.remove('active'));
            pill.classList.add('active');
            var radio = pill.querySelector('input[type=radio]');
            if (radio) radio.checked = true;
        });
    });
</script>

</body>
</html>
