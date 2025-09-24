<?php
    session_start();
    include "connection.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $birthdate = $_POST['birthdate'];
        $password = $_POST['password'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
    }

    $stmt = $conn->prepare("SELECT id FROM user WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        header('Location: registrer.php?error=invalid');
        exit();
    } else {
        $smtm = $conn->prepare("INSERT INTO user (username, email, birthdate, password) VALUES (?, ?, ?, ?)");
        $smtm->bind_param("ssss", $username, $email, $birthdate, $hash);
        $smtm->execute();
        $smtm->close();
        echo("Successfully registered, please login.");
        header("Location: login.php");
        exit();
    }
?>