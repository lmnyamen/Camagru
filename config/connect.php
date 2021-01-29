<?php
    // session_start();
    // connecting to database

    include_once "config.php";
    
    // $DB_DSN ="localhost";
    // $DB_USER = "root";
    // $DB_PASSWORD = "lmnyamen";
    // $DB_NAME = "camagru";

    try {
        $conn = new PDO("mysql:dbname=$DB_NAME; host=$DB_DSN",$DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "error:".$e->getMessage(); 
    }
?>