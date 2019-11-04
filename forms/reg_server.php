<?php

session_start();

// connecting to database

$DB_DSN ="localhost";
$DB_USER = "root";
$DB_PASSWORD = "lmnyamen";
$DB_NAME = "camagru";

try {
    $conn = new PDO("mysql:dbname=$DB_NAME; host=$DB_DSN",$DB_USER, $DB_PASSWORD);
    $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // register user to the database
    if( $_POST && isset($_POST['register_user'])) { 
        
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password_1 = htmlspecialchars($_POST['password_1']);
        $password_2 = htmlspecialchars($_POST['password_2']);
        $vcode = md5(rand(0,1000));
 

        // form validation
        $errors = array();
        if (empty($email)) {$errors[] = "Email  is required";}
        if (filter_var($email, Filter_VALIDATE_EMAIL)){$errors[] = "Wromg email format";}
        
        if ($password_1 != $password_2) {$errors[] = "Passwords do not match";}

        if (ctype_alnum($password_1) === false || is_numeric($password_1) === true || ctype_alpha($password_1) === true)
        {$errors[] = "A password  must consist of alphanumeric characters.";}
        if (strlen($password_1) < 8) {$errors[] = "Minimun of 8 characters for password";}
        if (ctype_alnum($username) === false) {$errors[] = "A Username can only consist of alphanumeric characters.";}
        $password = $password_1;   

        $sql = "SELECT * FROM users WHERE username = '$username' AND email = '$email' LIMIT 1";
        $user = $conn->prepare($sql);
        $user->execute();
        $results = $user->fetchALL();
        

        if ($results){
            // echo "|->".$results[0]['username']."<-|<br>";
            //  $_SESSION['id'] = $results[0]['id'];
            if ($results[0]['username'] === $username) {$errors[] = "Username already exist";}
            if ($results[0]['email'] === $email) {$errors[] = "Email has a registered username";}

        }

        if (empty($errors) === true){

            $sql = "INSERT INTO users (username, email, password, vcode) VALUES ('$username', '$email', '$password', '$vcode')";
            // use exec() because no results are returned
            $conn->exec($sql);
            echo "New record created successfully . <br>";

            sendemail($email, $vcode);
    
            $_SESSION['username'] = $username;
            // $_SESSION['id'] = $id;
            // $_SESSION['success'] = "you are registered";

            // header('location: ../index.php');
            echo "An email has been sent to your address, Kindly verify your email";
        }
        else {
                foreach($errors as $stmt){
                    echo "$stmt <br>";
                }
                
        }
    }
    // email verification
    if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['vcode']) && !empty($_GET['vcode'])) 
    {
        $email = htmlspecialchars($_GET['email']);
        $vcode = htmlspecialchars($_GET['vcode']);

        $sql = "SELECT * FROM users WHERE email = '$email' AND vcode = '$vcode' AND active ='0' LIMIT 1";
        $user = $conn->prepare($sql);
        $user->execute();
        $match = $user->rowCount();

        if ($match > 0)
        {
            $sql = "UPDATE users SET active='1' WHERE email ='$email' AND  vcode ='$vcode'";
            $user = $conn->prepare($sql);
            $user->execute();
            echo " your account has been activated please log in";
            echo "<p> <a href='login_form.php'> <b>Log in </b></a></p";
        }
    }

    // log in a user that is already in the database

    if($_POST && isset($_POST['login_user'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password_1']);

        echo "ola <br>";
      
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND active ='1' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchALL();
        $num_rows = $stmt->rowCount();
      
        $_SESSION['id'] = $results[0]['id'];
        $_SESSION['vcode'] = $results[0]['vcode'];

        if($num_rows > 0){ 
            
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "logged in successfully";
    
            header('location: ../index.php');
        }
        else {
            echo "<h1>Error</h1>";
            echo "<P> Sorry, your account could not be found OR It is not yet verified.</p>";
            echo "<p> <a href='reg_form.php'> <b> Register here. </b></a></p>";
        }
    }

    // reset password
    if ($_POST && isset($_POST['reset_password'])){
        $email = htmlspecialchars($_POST['email']);
        $password_1 = htmlspecialchars($_POST['password_1']);
        $password_2 = htmlspecialchars($_POST['password_2']);


        $errors = array();
        if (empty($email)) {$errors[] = "Email  is required";}
        if (filter_var($email, Filter_VALIDATE_EMAIL)){$errors[] = "Wromg email format";}
        
        if ($password_1 != $password_2) {$errors[] = "Passwords do not match";}

        if (ctype_alnum($password_1) === false || is_numeric($password_1) === true || ctype_alpha($password_1) === true)
        {$errors[] = "A password  must consist of alphanumeric characters.";}
        if (strlen($password_1) < 8) {$errors[] = "Minimun of 8 characters for password";}
        $password = $password_1;

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $no_row = $stmt->rowCount();

        if ($no_row > 0)
        {
            $results = $stmt->fetchAll();
            if ($results)
            {   
                // print_r($results);
                if ($results[0]['email'] === $email  && empty($errors) === true) 
                {
                    $sql = "UPDATE users SET password ='$password' WHERE email ='$email'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    echo "your password has been changed please log in";
                    echo "<p> <a href='login_form.php'> <b>Log in </b></a></p";

                }
                else
                {
                    echo "Email not found . <br>";
                    foreach( $errors as $stmt)
                        echo " $stmt . <br>";
                    echo "<p> <a href='reg_form.php'> <b> Register here. </b></a></p>";
                }

            }
        }

    }

    // update user information
    if( $_POST && isset($_POST['update_form'])) { 
        
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password_1 = htmlspecialchars($_POST['password_1']);
        $password_2 = htmlspecialchars($_POST['password_2']);
 

        // form validation
        $errors = array();
        if (empty($email) === false) {
            if (filter_var($email, Filter_VALIDATE_EMAIL)){$errors[] = "Wromg email format";}
        }
        if (empty($password_1) === false) {
            if ($password_1 != $password_2) {$errors[] = "Passwords do not match";}
            if (ctype_alnum($password_1) === false || is_numeric($password_1) === true || ctype_alpha($password_1) === true)
                {$errors[] = "A password  must consist of alphanumeric characters.";}
            if (strlen($password_1) < 8) {$errors[] = "Minimun of 8 characters for password";}
        }
        if (empty($username) === false) {
            if (ctype_alnum($username) === false) {$errors[] = "A Username can only consist of alphanumeric characters.";}
        }
        $password = $password_1;   

        $sql = "SELECT * FROM users WHERE username = '$username' AND email = '$email' LIMIT 1";
        $user = $conn->prepare($sql);
        $user->execute();
        $results = $user->fetchALL();
        

        if ($results){
            // echo "|->".$results[0]['username']."<-|<br>";
            if ($results[0]['username'] === $username) {$errors[] = "Username already exist";}
            if ($results[0]['email'] === $username) {$errors[] = "Email has a registered username";}

        }

        if (empty($errors) === true){


            $id =  $_SESSION['id'];
            $vcode =  $_SESSION['vcode'];
 

            if (empty($username) === false)
            {
                $shit = "username";
                $sql = "UPDATE users SET username ='$username' WHERE id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();
                $_SESSION['username'] = $username;

                //call function
                updateemail($email, $shit);

              
              
            }
            if (empty($email) === false)
            {
                $shit = "email";
                $sql = "UPDATE users SET email ='$email' WHERE id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();
          
            }
            if (empty($password) === false)
            {
                $shit = "password";
                $sql = "UPDATE users SET password ='$password' WHERE id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();
                // exit();
            }

            
            header('location: ../index.php');
        }
        else {
                foreach($errors as $stmt){
                    echo "$stmt <br>";
                }
                
        }
    }

}
catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();  
}

function sendemail($email, $vcode)
{
    $to_email = $email;
    $subject = 'verify account';
    $message = '
 
    Thanks for signing up!
    
    Please click this link to activate your account:
    http://localhost:8080/camagru/forms/reg_server.php?email='.$email.'&vcode='.$vcode.'
    
    '; // Our message above including the link
    $headers = 'From: admin @ camagru . com';
    mail($to_email,$subject,$message,$headers);
    // echo "email sent";
}

function updateemail($email, $shit)
{
    $to_email = $email;
    $subject = 'Update';
    $message = '
    You updated your shit Remember THAT!!</br>'. $shit.' ';
    $headers = 'From: admin @ camagru . com';
    mail($to_email,$subject,$message,$headers);
    // echo "email sent";
}

?>