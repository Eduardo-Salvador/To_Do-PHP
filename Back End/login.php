<?php 
    session_start();
    include __DIR__ . "/../Data Base/connection.php";

    $EMAIL = trim($_POST['email'] ?? '');
    $PASS = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_user, email, password FROM user WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $EMAIL);
    $stmt->execute();
    $result = $stmt->get_result();

    if(empty($EMAIL) || empty($PASS)) {
        header('Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/Login/login.html?error=invalid');
        exit();
    } else if(strtolower($USER) == "admin" && $PASS == "123"){
        $_SESSION['username'] = 'admin';
        header("Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/admin.php");
        exit();
    } else if($row = $result->fetch_assoc()) {
        if(password_verify($PASS, $row['password'])){
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['email'] = $row['email'];
            header('Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/Initial Tab - To Do/index.html');
            exit();
        } else {
            header('Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/Login/login.html?error=invalidpassword');
            exit();
        }
    }
    else {
        header('Location: /Desenvolvimento%20de%20Sistemas%20Web%201/To_Do-PHP/Login/login.html?error=invalid');
        exit();
    }
?>