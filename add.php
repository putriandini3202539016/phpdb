<?php
include_once("config.php");

$errors = [];
$success = false;

if (isset($_POST['Submit'])) {
    // ambil input dan trim
    $nim = trim($_POST['nim']);
    $nama = trim($_POST['nama']);
    $umur = trim($_POST['umur']);
    $jeniskelamin = trim($_POST['jeniskelamin']);
    $asalsekolah = trim($_POST['asalsekolah']);

    // validasi sederhana
    if ($nim === '' || $nama === '') {
        $errors[] = "NIM dan Nama wajib diisi.";
    }

    if (!is_numeric($umur) || intval($umur) <= 0) {
        $errors[] = "Umur harus berupa angka positif.";
    }

    if (empty($errors)) {
        // gunakan prepared statement untuk keamanan
        $stmt = mysqli_prepare($mysqli, "INSERT INTO mahasiswati (nim,nama,umur,jeniskelamin,asalsekolah) VALUES (?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "ssiss", $nim, $nama, $umur, $jeniskelamin, $asalsekolah);
        $ok = mysqli_stmt_execute($stmt);
        if ($ok) {
            $success = true;
            // reset fields
            $nim = $nama = $umur = $jeniskelamin = $asalsekolah = '';
        } else {
            $errors[] = "Gagal menyimpan data: " . mysqli_error($mysqli);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tambah Data Mahasiswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root{
            --pastel-blue:#d7e9f7;
            --accent:#5aa6df;
        }
        body { background: linear-gradient(180deg, #eef8ff 0%, var(--pastel-blue) 100%); font-family:'Inter',sans-serif; }
        .form-card { max-width:720px; margin:50px auto; border-radius:12px; background:#fff; padding:28px; box-shadow: 0 10px 30px rgba(17,82,135,0.06);}
        .gender-pill { cursor:pointer; border-radius:8px; padding:10px 14px; border:1px solid #e6eef9; }
        .gender-pill.active { background:var(--accent); color:white; border-color:var(--accent); }
        .icon-large { font-size:28px; }
    </style>
</head>
<body>
<div class="container">
    <div class="form-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="bi bi-person-plus me-2 text-primary"></i> Tambah Mahasiswa</h4>
            <a href="index.php" class="btn btn-link">Kembali ke Daftar</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">Mahasiswa berhasil ditambahkan. <a href="index.php">Lihat daftar</a></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="add.php" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" class="form-control" value="<?php echo isset($nim) ? htmlspecialchars($nim) : ''; ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?php echo isset($nama) ? htmlspecialchars($nama) : ''; ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Umur</label>
                <input type="number" min="1" name="umur" class="form-control" value="<?php echo isset($umur) ? htmlspecialchars($umur) : ''; ?>" required>
            </div>

            <div class="col-md-8">
                <label class="form-label">Jenis Kelamin</label>
                <div class="d-flex gap-2">
                    <label class="gender-pill d-flex align-items-center gap-2 px-3" id="pill-l">
                        <i class="bi bi-gender-male icon-large"></i>
                        <input type="radio" name="jeniskelamin" value="laki-laki" class="d-none" <?php if(isset($jeniskelamin) && $jeniskelamin=='laki-laki') echo 'checked'; ?>>
                        <span>Laki-Laki</span>
                    </label>

                    <label class="gender-pill d-flex align-items-center gap-2 px-3" id="pill-p">
                        <i class="bi bi-gender-female icon-large"></i>
                        <input type="radio" name="jeniskelamin" value="perempuan" class="d-none" <?php if(isset($jeniskelamin) && $jeniskelamin=='perempuan') echo 'checked'; ?>>
                        <span>Perempuan</span>
                    </label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Sekolah Asal</label>
                <input type="text" name="asalsekolah" class="form-control" value="<?php echo isset($asalsekolah) ? htmlspecialchars($asalsekolah) : ''; ?>">
            </div>

            <div class="col-12 text-end">
                <button type="submit" name="Submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</button>
            </div>
        </form>
    </div>
</div>

<script>
    // interaktif gender pill
    document.querySelectorAll('.gender-pill').forEach(function(pill){
        pill.addEventListener('click', function(){
            document.querySelectorAll('.gender-pill').forEach(p=>p.classList.remove('active'));
            pill.classList.add('active');
            // check radio
            var radio = pill.querySelector('input[type=radio]');
            if (radio) radio.checked = true;
        });
    });

    // set active based on preselected value (server-side)
    window.addEventListener('load', function(){
        var checked = document.querySelector('input[name="jeniskelamin"]:checked');
        if (checked) {
            checked.closest('.gender-pill').classList.add('active');
        }
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
