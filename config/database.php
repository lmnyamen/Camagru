<?PHP
include_once('./config.php');

// $DB_DSN = "localhost";
// $DB_USER = "root";
// $DB_PASSWORD = "lmnyamen";
// $DB_NAME = "camagru";

try {
    $conn = new PDO("mysql:host=$DB_DSN", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME";
    $conn->exec($sql);
    echo "Database created successfully";

    $conn = new PDO("mysql:dbname=$DB_NAME; host=$DB_DSN", $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOExeption $e) {
    echo $sql . "<br>" . $e->getMessage();
}
header('location: setup.php');

?>