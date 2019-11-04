<?PHP
$DB_DSN = "localhost";
$DB_USER = "root";
$DB_PASSWORD = "lmnyamen";
// $DB_NAME = "camagru";

try {
    $conn = new PDO("mysql:host=$DB_DSN", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DROP DATABASE IF EXISTS camagru; CREATE DATABASE camagru";
    $conn->exec($sql);
    echo "Database created successfully";
} catch(PDOExeption $e) {
    echo $sql . "<br>" . $e->getMessage(); 
}
header('location: tables.php');

$conn = null;

?>