<?php
    session_start();
    include 'connect.php';
    $username=$_POST['username'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $password=$_POST['password'];
    $confirmPassword=$_POST['confirmPassword'];
    $error="";
    if($password!=$confirmPassword)
        {
            $error.="Passwords do not match. ";
        }
        $check="select * from users where email='$email' or phone='$phone' or username='$username'";
        $res=mysqli_query($conn,$check);
        if(mysqli_num_rows($res)>0)
            {
                $error.="User already exists. ";
            }
            if($error!="")
                {
                    echo "<script>
                        alert('$error');
                        window.location.href='register.php';
                    </script>";
                    exit();
                }
            $sql="insert into users(username,email,phone,password) values('$username','$email','$phone','$password')";
            mysqli_query($conn,$sql);
            $_SESSION['users_id']=mysqli_insert_id($conn);
            $_SESSION['username']=$username;
            $_SESSION['email']=$email;
            $_SESSION['phone']=$phone;
            header("Location:chat.php");
            exit();
?>