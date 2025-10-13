<?php
    session_start();
    include __DIR__ . "/../Model/connection.php";

    if (!isset($_SESSION['id_user'])) {
    // Se não estiver logado, redireciona para a página de login
        header('Location: ../Controller/Login/login.html');
        exit();
    }

    $res = $conn->prepare("SELECT username, email, birthdate FROM user ORDER BY username ASC");
    $res->execute();
    $result = $res->get_result();


?>