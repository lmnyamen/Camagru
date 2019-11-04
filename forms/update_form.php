<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Camagru</title>
</head>
<body>
    <div class="container">
        <div>
            <h2> Update your user information <h2>
        </div>
            <form action="reg_server.php" method="post">
                

            <div>
                <label for="username"> Username : </label>
                <input type="text" name="username">
            </div>  
            <div>
                <label for="email"> Email : </label>
                <input type="text" name="email">
            </div>
            <div>
                <label for="passsword"> Password : </label>
                <input type="text" name="password_1">
            </div>
            <div>
                <label for="passsword"> Confirm Password : </label>
                <input type="text" name="password_2">
            </div>
            <button type="submit" name="update_form"> Submit </button>
            <!-- <p> Already a user? <a href="login_form.php"> <b>Log in </b></a></p>   -->
            </form>
    </div>
</body>
</html>