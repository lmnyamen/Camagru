<?php
    session_start();

    // connecting to database
    
    $DB_DSN ="localhost";
    $DB_USER = "root";
    $DB_PASSWORD = "";
    $DB_NAME = "camagru";
    
    try {
        $conn = new PDO("mysql:dbname=$DB_NAME; host=$DB_DSN",$DB_USER, $DB_PASSWORD);
        $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        if (!isset($_POST['json']))
            echo "error:".$e->getMessage();
        else
            echo '{"result": "failed"}'; 
    }

    if (isset($_POST['upload'], $_FILES['image']) && !empty($_POST['upload']) && !empty($_FILES['image']["name"])){ 

        $folder = "../uploads/";
        // $image = $_FILES['image']['name'];
        $username = $_SESSION['username'];
        $user_id = $_SESSION['id'];
        // $paths = $folder . $image;
        $filename = md5(date("1").$_FILES['image']['tmp_name']) . '.' . explode('/', $_FILES['image']['type'])[1];

        $_SESSION['image'] = $filename;

        $target_file = $folder . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_FILENAME));
        $extansions_arr = array("jpg","jpeg","png","gif");

        // echo $target_file;
        $ext = explode('/',$_FILES["image"]["type"]);
        $imageFileType = $ext[1];
        // var_dump($ext);
        // ar_dump($_FILES);
        // echo $imageFileType;
        // die();
        if (in_array($imageFileType, $extansions_arr)){

            $sql = "INSERT INTO images (image, username, user_id) VALUES ('$filename', '$username', '$user_id')";
            $user = $conn->prepare($sql);
            $user->execute([$filename]);

            if (!isset($_POST['json']))
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            else {
                file_put_contents($target_file, file_get_contents($_FILES['image']['tmp_name']));
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
                echo '{"result": "success"}';
                exit();
            }
        }
        if (!isset($_POST['json']))
            header('location: ../homepage.php');
        else
            echo '{"result": "failed"}';
    }
    else
        echo "ERROR"."</br>"."Select an image";


?>