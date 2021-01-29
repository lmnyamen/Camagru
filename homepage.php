<?PHP
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username']))
    header('location: forms/login_form.php');


function like_count($image_id)
{
    // $conn = connectDB();
    $DB_DSN = "localhost";
    $DB_USER = "root";
    $DB_PASSWORD = "";
    $DB_NAME = "camagru";
    $conn = null;
    try {
        $conn = new PDO("mysql:dbname=$DB_NAME; host=$DB_DSN", $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "error:" . $e->getMessage();
    }

    if ($conn == null)
        echo "connection failed";

    $sql = "SELECT * FROM likes WHERE image = '$image_id'";
    $user = $conn->prepare($sql);
    $user->execute();

    $results = $user->fetchALl();
    $row_count = $user->rowCount();

    return ($row_count);
}

include_once "config/connect.php";
// retrieve all pictures from the database
$sql = "SELECT image FROM images";
$user = $conn->prepare($sql);
$user->execute();
$results = $user->rowCount();

//set number of pictures per page
$results_per_page = 5;
//determine the number of pages needed
$number_of_pages = ceil($results / $results_per_page);
$current_page = 1;
// determine what page number the visitor is currently on
if (!isset($_GET['page'])) {
    $currrent_page = 1;
} else {
    $current_page = $_GET['page'];
}
// determine the SQL limit starting number for the results on the displaying page
$start_limit = ($current_page - 1) * $results_per_page;
// retrieve data from the database
// $sql = "SELECT * FROM images ORDER BY id DESC LIMIT $start_limit , $results_per_page";
// $user = $conn->prepare();
// $user->execute();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Camagru</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <div class="main_page">
        <div>
            <h1> CAMAGRU </h1>
            <?PHP
            if (isset($_SESSION['username'])) :
                ?>
                <h3> Welcome <?php echo $_SESSION['username']; ?></h3>
                <button>
                    <a href="images/upload_form.php"> <b> upload an image</b></a>
                </button>
                <button>
                    <a href="forms/update_form.php"> <b> Update user info </b></a>
                </button>
                <button>
                    <a href="forms/logout.php"> <b>log out </b> </a>
                </button>
            <?php endif ?>
        </div>
        <div>
            <h1> Gallery of Camagru </h1>
            <table border="1">
                <?php
                include_once "config/connect.php";
                // include "images/function.php";
                
                $sql = $conn->prepare("SELECT * FROM images ORDER BY id DESC LIMIT $start_limit, $results_per_page");
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                $sql->execute();
                while ($data = $sql->fetch()) {
                    ?><th>
                    <td>
                        <button>
                            <a href="http://localhost:8080/camagru/images/likes.php?username=<?php echo  $_SESSION['username'] ?> &image=<?php echo $data['image'] ?> &id=<?php echo $_SESSION['id'] ?>">likes</a>
                            <button> <?php echo like_count($data['image']); ?> </button>
                        </button>
                        <button>
                            <a href="http://localhost:8080/camagru/images/comments.php?username=<?php echo  $_SESSION['username'] ?> &image=<?php echo $data['image'] ?> &id=<?php echo $_SESSION['id'] ?>">comment</a>
                            <!-- <button>view</button> -->
                        </button>
                        <img src="uploads/<?php echo $data['image']; ?>" width="150" height="150">
                        <button>
                            <a href="http://localhost:8080/camagru/images/delete.php?username=<?php echo  $_SESSION['username'] ?> &image=<?php echo $data['image'] ?> &id=<?php echo $_SESSION['id'] ?>">delete</a>
                        </button>
                    </td>
                <?php
                } ?></th>
            </table>
        </div>
        <div class="page_numbers">
            <?php  // display links to pages
                for ($page = 1; $page <= $number_of_pages; $page++)
                echo "<a href='homepage.php?page=" . $page . "'>" . $page . "-</a>";
            ?>
        </div>
        <hr>
        <div class="booth">
            <table border="0">
                <th>
                <td>
                    <div class="stream_container">
                        <video id="video" width="400" height="300"> </video>
                        <a href="#" id="capture" class="capture_button"> take photo</a>
                        <div class="preview_container">
                            <div id="preview">
                                <img src="images/stickers/empty.png" id="supImage" width="100px" height="50px" />
                            </div>
                            <div id="preview1">
                                <img src="images/stickers/bunny.png" id="supImage1" width="100px" height="50px" />
                            </div>
                        </div>
                    </div>
                <td>
                    </th>
                <th>
                <td>
                    <canvas id="canvas" width="400" height="300"></canvas>
                </td>
                </th>
            </table>
        </div>
        <!-- <canvas id="canvas" width="400" height="300"></canvas> -->
        <div id="thumbnail" class="thumbnail">
            <img src="images/stickers/devil.png" width="100px" height="50px" />
            <img src="images/stickers/dog.png" width="100px" height="50px" />
            <img src="images/stickers/bunny.png" width="100px" height="50px" />
            <img src="images/stickers/empty.png" width="100px" height="50px" />

        </div>
    </div>
    <script src="images/cam.js"></script>
    <script>
        const children = document.getElementById('thumbnail').childNodes,
            sup = document.getElementById('supImage'),
            sup1 = document.getElementById('supImage1');
        let point = 1;

        for (let i = 0, n = children.length; i < n; i++) {
            const child = children[i];

            child.addEventListener('click', function(e) {
                if (point === 1)
                    document.getElementById('supImage').src = e.target.src;
                else
                    document.getElementById('supImage1').src = e.target.src;
            });
        }

        sup.addEventListener('click', function(e) {
            point = 1;
        });

        sup1.addEventListener('click', function(e) {
            point = 2;
        });
    </script>
    </div>
    <div class="footer">
        <?php include "footer.php" ?>
    </div>
</body>

</html>