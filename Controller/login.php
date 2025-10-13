<?php
session_start();
include __DIR__ . "/../Model/connection.php";

$adminEmail = 'admin@admin.com';
$adminPassword = 'admin123';

$checkAdmin = $conn->prepare("SELECT id_user FROM user WHERE email = ? LIMIT 1");
$checkAdmin->bind_param('s', $adminEmail);
$checkAdmin->execute();
$resultAdmin = $checkAdmin->get_result();

if ($resultAdmin->num_rows === 0) {
    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
    $insertAdmin = $conn->prepare("
            INSERT INTO user (username, email, password, birthdate)
            VALUES (?, ?, ?, NULL)
        ");
    $adminName = 'Admin';
    $insertAdmin->bind_param('sss', $adminName, $adminEmail, $hashedPassword);
    $insertAdmin->execute();
}

$EMAIL = trim($_POST['email'] ?? '');
$PASS = $_POST['password'] ?? '';

if (empty($EMAIL) || empty($PASS)) {
    header('Location: ../View/login.html?error=emptyfields');
    exit();
}

$stmt = $conn->prepare("SELECT id_user, username, email, password FROM user WHERE email = ? LIMIT 1");
$stmt->bind_param('s', $EMAIL);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($PASS, $row['password'])) {
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['username'] = $row['username'];

        if (strtolower($row['email']) == 'admin@admin.com') {
            header('Location: ../Controller/admin.php');
        } else {
            header('Location: initialtab.php');
        }
        exit();
    } else {
        header('Location: ../View/login.html?error=invalidpassword');
        exit();
    }
} else {
    header('Location: ../View/login.html?error=invaliduser');
    exit();
}
