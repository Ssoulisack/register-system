<?php
session_start();
include('server.php');

$errors = array();

    if(isset($_POST['reg_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']);

        if(empty($username)) {
            array_push($errors, "Username is required");
        }
        if(empty($email)) {
            array_push($errors, "Email is required");
        }
        if(empty($password_1)) {
            array_push($errors, "Password is required");
        }
        if($password_1 != $password_2) {
            array($errors, "The two passwords don't match");
        }

        $user_check_query = "SELECT * FROM users WhERE username = '$username' OR email = '$email' ";
        $query = mysqli_query($conn, $user_check_query);
        $result = mysqli_fetch_assoc($query);
        
        if($result) { // if user exits
            if($result['username'] === $username) {
                array_push($errors, "username already exits");
            }
            if ($result['email'] === $email){
                array_push($errors, "Email already exits");
            }
        }

        if (count($errors) == 0) {
            $password= md5($password_1);

            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            mysqli_query($conn, $sql);

            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        } else {
            array_push($errors, "Username or Email. already exits");
                $_SESSION['error'] =  "Username or Email. already exits!";
                header("location: register.php");
        }
    }
?>