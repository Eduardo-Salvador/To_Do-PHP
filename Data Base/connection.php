<?php
    $server = 'localhost';
    $user = 'root';
    $password = '';
    $bank = 'todo';
    $conn = new mysqli($server, $user, $password, $bank);
    if ($conn->connect_error) { 
        die('Error: ' . $conn->connect_error); 
    }
?>