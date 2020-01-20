
<?php
    if(isset($_SESSION['username']))
    header('location: homepage.php');

?>
<!-- <!DOCTYPE html> -->
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
            <h2> Register  <h2>
        </div>
            <form action="reg_server.php" method="post">
                

            <!-- <div> -->
                <!-- <label for="username"> Username : </label> -->
                <input type="text" name="username" placeholder="Enter Username" required>
            <!-- </div>   -->
            <!-- <div> -->
                <!-- <label for="email"> Email : </label> -->
                <input type="mail" name="email" placeholder="Enter Email"  >
            <!-- </div> -->
            <!-- <div> -->
                <!-- <label for="passsword"> Password : </label> -->
                <input type="password" name="password_1" placeholder="Enter Password" >
            <!-- </div> -->
            <!-- <div> -->
                <!-- <label for="passsword"> Confirm Password : </label> -->
                <input type="password" name="password_2" placeholder="Confirm Password" >
            <!-- </div> -->
            <div>
                <label for="notification"> Email notification: </label>
                <input type="radio" name="notification" value="yes" required> Yes
                <input type="radio" name="notification" value="no"  required> No
            </div>

            <button type="submit" name="register_user"> Submit </button>
            <p> Already a user? <a href="login_form.php"> <b>Log in </b></a></p  
            </form>
    </div>
</body>
</html>