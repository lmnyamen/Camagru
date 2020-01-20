

<?php     

//call this  fucntion in ur loop for displaying images where you have all tha images information
// function like_counter(image name ){
// $SQL ="SELECT  * WHERE image = ?";
// excute here;
// count row after fetch ;
// and return count value; 

// which you should display in ur html after;
// }
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

function like_count($image_id){
    // $conn = connectDB();
    include "../config/connect.php";


    $sql = "SELECT * FROM likes WHERE image = '$image_id'";
    $user = $conn->prepare($sql);
    $user->execute();

    $results = $user->fetchALl();
    $row_count = $user->rowCount();

    return($row_count);

}

?>