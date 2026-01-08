<?php
include_once("config.php");
if (!isset($_GET['no'])) {
    header("Location: index.php"); exit;
}
$no = intval($_GET['no']);
$stmt = mysqli_prepare($mysqli, "DELETE FROM mahasiswati WHERE no = ?");
mysqli_stmt_bind_param($stmt, "i", $no);
mysqli_stmt_execute($stmt);
header("Location: index.php");
exit;
?>
