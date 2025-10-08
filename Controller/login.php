<?php 
    session_start();
    include __DIR__ . "/../Model/connection.php";

    $EMAIL = trim($_POST['email'] ?? '');
    $PASS = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id_user, email, password FROM user WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $EMAIL);
    $stmt->execute();
    $result = $stmt->get_result();

    if(empty($EMAIL) || empty($PASS)) {
        header('Location: ../View/login.html?error=emptyfields');
        exit();
    } else if(strtolower($EMAIL) == "admin@example.com" && $PASS == "123"){
        $_SESSION['username'] = 'admin';
        header("admin.php");
        exit();
    } else if($row = $result->fetch_assoc()) {
        if(password_verify($PASS, $row['password'])){
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['email'] = $row['email'];
            header('Location: initialtab.php');
            exit();
        } else {
            header('Location: ../View/login.html?error=invalidpassword');
            exit();
        }
    }
    else {
        header('Location: ../View/login.html?error=invaliduser');
        exit();
    }
?>
