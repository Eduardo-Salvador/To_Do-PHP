<?php 
    session_start();
    $USER = $_POST['username'];
    $PASS = $_POST['password'];
    if(empty($USER) || empty($PASS)) {
        header('login.php');
    } else if($USER == "admin" || $USER == "Admin"){
        if($PASS == "123"){
            header("admin.php");
            exit();
        }
    } else if($USER == "" || $PASS == ""){
        header('index.php');
        exit();
    }
?>