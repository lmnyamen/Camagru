<?php

session_start();

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

include "../config/connect.php";
include "function.php";

if ((isset($_GET['username']) && !empty($_GET['username'])) AND (isset($_GET['image']) && !empty($_GET['image'])) AND (isset($_GET['id']) && !empty($_GET['id'])))
{
    $username = $_GET['username'];
    $image_id = $_GET['image'];
    $user_id = $_GET['id'];
    
    // echo $user_id;
    // echo $image_id;

    $sql = "SELECT * FROM likes WHERE user_id ='$user_id' AND image = '$image_id'";
    $user = $conn->prepare($sql);
    $user->execute();
    $results = $user->fetchALL();
    $num_rows = $user->rowCount();


    if ($num_rows == 0)
    {
        $sql = "INSERT INTO likes (username, image, user_id ) VALUES ('$username', '$image_id', '$user_id')";
        $user = $conn->prepare($sql);
        $user->execute();

        // send an emailNotification

        $sql = "SELECT username FROM images WHERE image ='$image_id'";
        $user = $conn->prepare($sql);
        $user->execute();
        $user_name = $user->fetch();

        $uname = $user_name['username'];

        // echo $uname . "</br>";

        $sql = "SELECT email, notification FROM users WHERE username='$uname'";
        $user = $conn->prepare($sql);
        $user->execute();

        $user_details = $user->fetch();

        // echo $user_details['email'] . "</br>";
        // echo $user_details['notification'];
        if ($user_details['notification'] === 'yes' && ($user_name['username'] != $_SESSION['username']))
            {
                $header = "Hi " . $user_name['username'] . "\n\n"; 
                $message = $_SESSION['username'] . " Just liked your photo. Click here to view http://localhost:8082/camagru";
                mail($user_details['email'], 'Notifications' ,$message, $header);
            }


    }
    else
    {
        $sql = "DELETE FROM likes WHERE user_id ='$user_id' AND image = '$image_id'";
        $user = $conn->prepare($sql);
        $user->execute();
    }
    // echo "you are here";
    // $likes = like_count($image_id);
    // echo $likes;
    header('location: ../homepage.php');
}


?>