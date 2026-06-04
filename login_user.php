<?php
 session_start();
include 'connect.php';
   $username=$_POST['username'];
   $password=$_POST['password'];
   $sql="select * from users where username='$username'";
   $res=mysqli_query($conn,$sql);
   if(mysqli_num_rows($res)>0)
    {
        $row=mysqli_fetch_assoc($res);
        if($password==$row['password'])
        {$_SESSION['users_id']=$row['id'];
        $_SESSION['email']=$row['email'];
        $_SESSION['phone']=$row['phone'];
        $_SESSION['username']=$row['username'];
        header("Location:chat.php");
        exit();
        }
    else
    {
        echo "<script>
            alert('Invalid password. Please try again.');
            window.location.href='login.php';
        </script>";
        exit();
    }
    }
    else
        {
            echo "<script>
            alert('Username not found. Please register first.');
            window.location.href='register.php';
            </script>";
            exit();
        }

?>