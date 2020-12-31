<?php
    include 'config.php';
    
    try {
        $dsn = "mysql:host=".$configDb['bd']['host']."; dbname=".$configDb['bd']['dbName'];
        $conn = new PDO($dsn, $configDb['bd']['user'], $configDb['bd']['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e){
        echo $e->getMessage();
    }