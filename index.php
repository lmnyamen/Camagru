<?PHP
session_start();
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
    <div>
            <div>
                    <h1> CAMAGRU </h1>
                    <button><a href="forms/reg_form.php"> <b> Register </b></a></button>
                    <button><a href="forms/login_form.php"> <b> Login</b></a></button>                    
            </div>
            <div><h1> Gallery of Camagru </h1>
                <table border="1">
                    <?php
                        include_once "./config/connect.php";
                        $sql = $conn->prepare("SELECT * FROM images;");
                        $sql->setFetchMode(PDO::FETCH_ASSOC);
                        $sql->execute();
                        while($data=$sql->fetch()){
                        ?><th>
                        <td> 
                            <img src="uploads/<?php echo $data['image']; ?>" width="150" height="150">
                        </td>
                        <?php
                        }?></th>
                </table>
            </div>
    </div>
    <div class="footer">
        <?php  include_once "footer.php" ?>
    </div>
</body>
</html>





