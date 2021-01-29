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
    // register user to the database
    if( $_POST && isset($_POST['register_user'])) { 
        
        $username = addslashes(trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8')));
        $email = addslashes(trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8')));
        $password_1 = addslashes(trim(htmlspecialchars($_POST['password_1'], ENT_QUOTES, 'UTF-8')));
        $password_2 = addslashes(trim(htmlspecialchars($_POST['password_2'], ENT_QUOTES, 'UTF-8')));
        $notification = trim(htmlspecialchars($_POST['notification'], ENT_QUOTES, 'UTF-8'));
        $vcode = md5(rand(0,1000));
 

        // form validation
        $errors = array();
        if (empty($email)) {$errors[] = "Email  is required";}
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){$errors[] = "Wromg email format";}
        
        if ($password_1 != $password_2) {$errors[] = "Passwords do not match";}

        if (ctype_alnum($password_1) === false || is_numeric($password_1) === true || ctype_alpha($password_1) === true)
        {$errors[] = "A password  must consist of alphanumeric characters.";}
        if (strlen($password_1) < 8) {$errors[] = "Minimun of 8 characters for password";}
        if (ctype_alnum($username) === false) {$errors[] = "A Username can only consist of alphanumeric characters.";}
        $password = password_hash($password_1, PASSWORD_DEFAULT);   

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

            $sql = "INSERT INTO users (username, email, password, vcode, notification) VALUES ('$username', '$email', '$password', '$vcode', '$notification')";
            // use exec() because no results are returned
            $conn->exec($sql);
            // echo "New record created successfully . <br>";
            
            $_SESSION['username'] = $username;
            sendemail($email, $vcode);
    
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
        $username = addslashes(trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8')));
        $password = (addslashes(trim(htmlspecialchars($_POST['password_1'], ENT_QUOTES, 'UTF-8'))));

        // echo "ola <br>";
      
        $sql = "SELECT * FROM users WHERE username = '$username' AND active ='1' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchALL();
        $num_rows = $stmt->rowCount();
      
        
        if($num_rows > 0 && password_verify($password, $results[0]['password'])){ 
            
            $_SESSION['id'] = $results[0]['id'];
            $_SESSION['vcode'] = $results[0]['vcode'];
            $_SESSION['email'] = $results[0]['email'];
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "logged in successfully";
    
            header('location: ../homepage.php');
        }
        else {
            echo "<h1>Error</h1>";
            echo "<P> Sorry, your account could not be found OR It is not yet verified.</p>";
            echo "<p> <a href='reg_form.php'> <b> Register here. </b></a></p>";
        }
    }

    // reset password
    if ($_POST && isset($_POST['reset_password'])){
        $email = addslashes(trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8')));
        $password_1 = addslashes(trim(htmlspecialchars($_POST['password_1'], ENT_QUOTES, 'UTF-8')));
        $password_2 = addslashes(trim(htmlspecialchars($_POST['password_2'], ENT_QUOTES, 'UTF-8')));


        $errors = array();
        if (empty($email)) {$errors[] = "Email  is required";}
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){$errors[] = "Wromg email format";}
        
        if ($password_1 != $password_2) {$errors[] = "Passwords do not match";}

        if (ctype_alnum($password_1) === false || is_numeric($password_1) === true || ctype_alpha($password_1) === true)
        {$errors[] = "A password  must consist of alphanumeric characters.";}
        if (strlen($password_1) < 8) {$errors[] = "Minimun of 8 characters for password";}
        $password = password_hash($password_1, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $no_row = $stmt->rowCount();

        if ($no_row > 0)
        {
            $results = $stmt->fetchAll();
            $vcode = $results[0]['vcode'];
            if ($results)
            {   
                // print_r($results);
                if ($results[0]['email'] === $email  && empty($errors) === true) 
                {
                    $sql = "UPDATE users SET password ='$password', active = '0' WHERE email ='$email'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    // echo "your password has been changed please log in";

                    // sendemail($email, $vcode);
                    
                    $header = "Hi " . "Camagru user" . "\n\n"; 
                    $message = ' you have changed your password. Click here to verify your email http://localhost:8080/camagru/forms/reg_server.php?email='.$email.'&vcode='.$vcode.'';
                    mail($email, 'Notifications' ,$message, $header);
                    // echo "<p> <a href='login_form.php'> <b>Log in </b></a></p";
                    echo "An email has been sent to your address, Kindly verify your email";
                    // header('location: ../index.php');

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
        
        $username = addslashes(trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8')));
        $email = addslashes(trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8')));
        $password_1 = addslashes(trim(htmlspecialchars($_POST['password_1'], ENT_QUOTES, 'UTF-8')));
        $password_2 = addslashes(trim(htmlspecialchars($_POST['password_2'], ENT_QUOTES, 'UTF-8')));
        $notification = trim(htmlspecialchars($_POST['notification'], ENT_QUOTES, 'UTF-8'));

 

        // form validation
        $errors = array();
        if (empty($email) === false) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){$errors[] = "Wromg email format";}
        }
        $password = '';
        if (empty($password_1) === false) {
            if ($password_1 != $password_2) {$errors[] = "Passwords do not match";}
            if (ctype_alnum($password_1) === false || is_numeric($password_1) === true || ctype_alpha($password_1) === true)
                {$errors[] = "A password  must consist of alphanumeric characters.";}
            if (strlen($password_1) < 8) {$errors[] = "Minimun of 8 characters for password";}
            $password = password_hash($password_1, PASSWORD_DEFAULT);
        }
        if (empty($username) === false) {
            if (ctype_alnum($username) === false) {$errors[] = "A Username can only consist of alphanumeric characters.";}
        }

        $sql = "SELECT * FROM users WHERE username = '$username' AND email = '$email' LIMIT 1";
        $user = $conn->prepare($sql);
        $user->execute();
        $results = $user->fetchALL();
        

        if ($results){
            // echo "|->".$results[0]['username']."<-|<br>";
            if ($results[0]['username'] === $username) {$errors[] = "Username already exist";}
            if ($results[0]['email'] === $email) {$errors[] = "Email has a registered username";}

        }

        if (empty($errors) === true){


            $id =  $_SESSION['id'];
            $vcode =  $_SESSION['vcode'];
            $em = $_SESSION['email'];
 

            if (empty($username) === false)
            {
                $shit = "username";
                $sql = "UPDATE users SET username ='$username' WHERE id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();
                
                $sql = "UPDATE comments SET username ='$username' WHERE user_id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();

                $sql = "UPDATE images SET username ='$username' WHERE user_id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();

                $sql = "UPDATE likes SET username ='$username' WHERE user_id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();

                $_SESSION['username'] = $username;

                //call function
                updateemail($em, $shit);
            }
            if (empty($email) === false)
            {
                $shit = "email";
                $sql = "UPDATE users SET email ='$email', active = '0' WHERE id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();

                $header = "Hi " . "Camagru user" . "\n\n"; 
                $message = ' you have changed your email. Click here to verify your email http://localhost:8080/camagru/forms/reg_server.php?email='.$email.'&vcode='.$vcode.'';
                mail($email, 'Notifications' ,$message, $header);

                echo "you have updated your email, Kindly verify it";
                exit();
          
            }
            if (empty($password) === false)
            {
                $shit = "password";
                $sql = "UPDATE users SET password ='$password' WHERE id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();

                updateemail($em, $shit);

                header('location: logout.php');
                exit();
            }
            if (empty($notification) === false)
            {
                $shit = "email notification";
                $sql = "UPDATE users SET notification = '$notification' WHERE id ='$id'";
                $user = $conn->prepare($sql);
                $user->execute();
                // exit();
            }
            
            header('location: ../homepage.php');
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
    // if ($user_details['notifications'] === 'Yes')
    //     {
    //         $header = "Hi " . $user_row['username'] . "\n\n"; 
    //         $message = $_SESSION['user_session'] . " Just comment on you photo. Click here to reply http://localhost:8080/camagru";
    //         mail($user_details['email'], 'Notifications' ,$message, $header);
    //     }
}

function updateemail($em, $shit)
{
    // $to_email = $email;
    // $subject = 'Update';
    // $message = '
    // You updated your' .$shit . 'Remember THAT!!</br>';
    // $headers = 'From: admin @ camagru . com';
    // mail($to_email,$subject,$message,$headers);
    // echo "email sent";
    $header = "Hi " . "Camagru user" . "\n\n"; 
    $message = ' you have updated your '.$shit. " ". 'Remember that! ';
    mail($em, 'Notifications' ,$message, $header);
}

?>