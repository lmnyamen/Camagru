
<?php
    if(isset($_SESSION['username']))
    header('location: homepage.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Camagru</title>
    <link rel="stylesheet" href="../css/forms.css">
</head>
<body>
    <div class="login">
        <div>
            <h2> Login <h2>
        </div>
            <form action="reg_server.php" method="post">
                

            <!-- <div> -->
                <!-- <label for="username"> Username : </label> -->
                <input type="text" name="username"  placeholder="Enter Username" required>
            <!-- </div>   -->
            <!-- <div> -->
                <!-- <label for="passsword"> Password : </label> -->
                <input type="password" name="password_1" placeholder="Enter password" required>
            <!-- </div> -->
            
            <button class="btn" type="submit" name="login_user"> Submit </button>
            <p> Not a user? <a href="reg_form.php"> <b> Register here. </b></a></p> 
            <p><a href="reset_password.php"> <b> forgot password</b></a></p>  

            </form>
    </div>
</body>
</html>