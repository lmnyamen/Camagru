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
</head>
<body>
    <div>
            <h1> FOLLOW THE RABBIT! </h1>
            <?PHP 
                    if(isset($_SESSION['username'])) :
            ?>
            <h3> Welcome <?php echo $_SESSION['username']; ?>, </br> RED or GREEN? </h3>
            <p><a href="forms/update_form.php"> <b> Update user info </b></a></p>  
            <?php endif ?>
    </div>
</body>
</html>





