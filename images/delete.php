<?PHP
session_start();

include "../config/connect.php";

if ((isset($_GET['username']) && !empty($_GET['image'])) AND (isset($_GET['image']) && !empty($_GET['image'])) AND (isset($_GET['id']) && !empty($_GET['id'])))
{
    $username = $_GET['username'];
    $image_id = $_GET['image'];
    $user_id = $_GET['id'];

    // echo $username . "<br>";
    // echo $image_id;
    // echo $id; 

    $sql = "SELECT * FROM images WHERE user_id = '$user_id' AND image = '$image_id' LIMIT 1";
    $user = $conn->prepare($sql);
    $user->execute();
    $results = $user->fetchALL();

    $num_rows = $user->rowCount();

    if ($num_rows > 0){
        // echo "you are here";
        $sql = "DELETE FROM images WHERE user_id = '$user_id' AND image = '$image_id'";
        $user = $conn->prepare($sql);
        $user->execute();
    }
    header('location: ../homepage.php');
}

?>