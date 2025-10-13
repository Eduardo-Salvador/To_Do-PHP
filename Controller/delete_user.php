<?php
include __DIR__ . "/../Model/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if (!$id) die("Unspecified user.");

    $stmt = $conn->prepare("DELETE FROM user WHERE id_user=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    header("Location: admin.php");
    exit();
}
?>