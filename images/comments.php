<?php
session_start();

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

// include "../config/connect.php";
if (!isset($_SESSION['username']))
    header('location: ../forms/login_form.php');

if ((isset($_GET['username']) && !empty($_GET['username'])) AND (isset($_GET['image']) && !empty($_GET['image'])) AND (isset($_GET['id']) && !empty($_GET['id'])))
{
    include "../config/connect.php";

    $username = $_GET['username'];
    $image_id = $_GET['image'];
    $user_id = $_GET['id'];

    // echo $username . "</br>";
    // echo $image_id . "</br>";
    // echo $user_id;
}

if ((isset($_POST['comment'])  && !empty($_POST['comment_input'])))
{
    include "../config/connect.php";

    $comment = htmlspecialchars($_POST['comment_input'], ENT_QUOTES, 'UTF-8');
    // echo "pizza3";

    // $comment = $_POST['comment_input'];
    $sql = "INSERT INTO comments (username, image, comment, user_id ) VALUES ('$username', '$image_id', '$comment', '$user_id')";
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
            $message = $_SESSION['username'] . " Just commented on your photo. Click here to reply http://localhost:8080/camagru";
            mail($user_details['email'], 'Notifications' ,$message, $header);
        }


    // header('location: ../homepage.php');

}
if ((isset($_GET['username']) && !empty($_GET['username'])) AND (isset($_GET['image']) && !empty($_GET['image'])))
{
    include "../config/connect.php";

    $username = $_GET['username'];
    $image_id = $_GET['image'];
    // echo $username . "</br>";
    // echo $image_id;

    $sql = "SELECT * FROM comments WHERE image='$image_id' ORDER BY reg_date LIMIT 18";
    $user = $conn->prepare($sql);
    $user->execute();

    if ($user->rowCount() >= 1)
        $comments = $user->fetchALL();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comment</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <div id="container">
        <header class="site-header">
            <a href="../homepage.php"><h1>Camagru</h1></a>
        </header>
        <div>
            <img src="../uploads/<?php echo $image_id; ?>" width="150" height="150">
        </div>
        <div>
            <textarea name="comment_input" form="comments-form"></textarea><br/>
            <form method="POST" id="comments-form">
                <input type="hidden" name="comments">
                <button type="submit" name="comment">Comment</button>
            </form>
          </div>
          <div>
          <?php
                if(isset($comments)){
                    echo "<br >";
                    foreach($comments as $comment)
                        echo "<p class='comment'><strong color='white'>".$comment['username']."</strong>: " .$comment['comment'] ."</p>";
                }
            ?>
          </div>
    </div>
    
</body>
</html>