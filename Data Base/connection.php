<?php
    $server = 'localhost';
    $user = 'root';
    $password = '';
    $bank = 'todo';
    $conn = new mysqli($server, $user, $password, $bank);
    if ($conn->connect_error) { 
        die('Error: ' . $conn->connect_error); 
    }

    /*
    INPUTS FROM CREATE DATA BASE:

    DATA BASE NAME: todo

    TABLE: user
    CREATE TABLE user (
        id_user INT PRIMARY KEY AUTO INCREMENT,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(100) NOT NULL,
        birthdate DATE
        )
    

    */
?>

