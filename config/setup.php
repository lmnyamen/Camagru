<?php
// $DB_DSN = "localhost";
// $DB_USER = "root";
// $DB_PASSWORD = "lmnyamen";
// $DB_NAME = "camagru";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('connect.php');

// try {
//     $conn = new PDO("mysql:dbname=$DB_NAME; host=$DB_DSN", $DB_USER, $DB_PASSWORD);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch(PDOExeption $e) {
//     echo $sql . "<br>" . $e->getMessage(); 
// }

try {
    // sql to  create a table 
    $sql = "DROP TABLE IF EXISTS users; CREATE TABLE users(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30),
        email VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        vcode VARCHAR(32) NOT NULL,
        notification VARCHAR(32) NOT NULL,
        active INT (1) NOT NULL DEFAULT '0',
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    // echo "Table Users Created successfully";

    $sql = "DROP TABLE IF EXISTS images; CREATE TABLE images(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        image VARCHAR(50) NOT NULL,
        username VARCHAR(30) NOT NULL,
        user_id INT(6) NOT NULL
    )";
    $conn->exec($sql);

    $sql = "DROP TABLE IF EXISTS likes; CREATE TABLE likes(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        image   VARCHAR(50) NOT NULL,
        user_id INT(6) NOT NULL
    )";
    $conn->exec($sql);

    $sql = "DROP TABLE IF EXISTS comments; CREATE TABLE comments(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        image   VARCHAR(50) NOT NULL,
        comment VARCHAR(250) NOT NULL,
        user_id INT(6) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
    )";
    $conn->exec($sql);
} catch (PDOExeption $e) {
    echo $sql . "<br>" . $e->getMessage();
}
header('location: ../index.php');

$conn = null;

?>