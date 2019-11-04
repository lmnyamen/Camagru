<?php
$DB_DSN = "localhost";
$DB_USER = "root";
$DB_PASSWORD = "lmnyamen";
$DB_NAME = "camagru";

try {
    $conn = new PDO("mysql:dbname=$DB_NAME; host=$DB_DSN", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOExeption $e) {
    echo $sql . "<br>" . $e->getMessage(); 
}

try {
    //sql to  create a table 
    $sql = "DROP TABLE IF EXISTS users; CREATE TABLE users(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(30) NOT NULL,
        vcode VARCHAR(32) NOT NULL,
        active INT (1) NOT NULL DEFAULT '0',
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
    $conn->exec($sql);
    // echo "Table Users Created successfully";
}
catch(PDOExeption $e){
    echo $sql . "<br>" . $e->getMessage(); 
}
header('location: ../forms/reg_form.php');

$conn = null;
?>

