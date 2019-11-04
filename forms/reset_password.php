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
            <h2> Reset Password. <h2>
        </div>
            <form action="reg_server.php" method="post">
                

            <div>
                <label for="email"> Email : </label>
                <input type="text" name="email" required>
            </div>  
            <div>
                <label for="passsword"> New Password : </label>
                <input type="text" name="password_1" required>
            </div>
            <div>
                <label for="passsword"> Confirm Password : </label>
                <input type="text" name="password_2" required>
            </div>
            
            <button type="submit" name="reset_password"> Submit </button>
            <p> Not a user? <a href="reg_form.php"> <b> Register here. </b></a></p> 

            </form>
    </div>
</body>
</html>