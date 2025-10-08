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

    CREATE TABLE user (
        id_user INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        birthdate DATE
    );

    CREATE TABLE tasks (
    id_task INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_done TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id_user)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

    */
?>

