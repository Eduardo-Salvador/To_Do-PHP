<?php 
    session_start();
    include "connection.php";
    $USER = trim($_POST['username'] ?? '');
    $PASS = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $USER);
    $stmt->execute();
    $result = $stmt->get_result();

    if(empty($USER) || empty($PASS)) {
        header('Location: login.php?error=invalid');
        exit();
    } else if(strtolower($USER) == "admin" && $PASS == "123"){
        $_SESSION['username'] = 'admin';
        header("Location: admin.php");
        exit();
    } else if($row = $result->fetch_assoc()) {
        if(password_verify($PASS, $row['password'])){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: index.php');
            exit();
        }
    } else {
        header('Location: login.php?error=invalid');
        exit();
    }
?>