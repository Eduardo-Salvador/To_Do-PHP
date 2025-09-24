<?php
    session_start();
    include "connection.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $birthdate = $_POST['birthdate'];
        $password = $_POST['password'];
    }

    $smtm = $conn->prepare("INSERT INTO user (username, email, birthdate, password) VALUES (?, ?, ?, ?)");
    $smtm->bind_param("ssds", $username, $email, $birthdate, $password);
    $smtm->execute();
    $smtm->close();

    header("Location: login.php");
    echo("Successfully registered, please login.");
?>