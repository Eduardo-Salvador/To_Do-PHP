<?php
    session_start();
    include __DIR__ . "/../Data Base/connection.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $password = $_POST['password'] ?? '';

        if(empty($username) || empty($email) || empty($birthdate) || empty($password)){
            header('Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/Register/register.html?error=emptyfields');
            exit();
        }

        $stmt = $conn->prepare("SELECT id_user FROM user WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->fetch_assoc()){
            header('Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/Register/register.html?error=userexists');
            exit();
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO user (username, email, birthdate, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $birthdate, $hash);
            $stmt->execute();
            $stmt->close();
            header('Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/Login/login.html?success=registered');
            exit();
        }
    } else {
        header('Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/Register/register.html?error=emptyfields');
        exit();
    }
?>