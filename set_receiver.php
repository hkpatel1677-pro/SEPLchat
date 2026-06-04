<?php
    include 'connect.php';
    include 'session.php';
    if(isset($_POST['receiver_id']))
        {
            $_SESSION['receiver_id']=$_POST['receiver_id'];
        }
        $current_user=$_SESSION['users_id'];
        unset($_SESSION['groups_id']);
        $receiver_id=$_SESSION['receiver_id'];
        $sql="update messages set flag='1' where receiver_id='$current_user' and users_id='$receiver_id'";
        // print_r($sql); die;
        mysqli_query($conn,$sql);
?>