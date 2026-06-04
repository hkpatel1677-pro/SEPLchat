<?php
    include 'connect.php';
    include 'session.php';
    $_SESSION['groups_id']=$_POST['groups_id'];
    $groups_id=$_SESSION['groups_id'];
    $current_user=$_SESSION['users_id'];
    unset($_SESSION['receiver_id']);
    $sql="update group_message_reads gmr JOIN messages m ON gmr.message_id=m.id set gmr.is_read='1' WHERE m.groups_id='$groups_id' AND gmr.users_id='$current_user'";
    mysqli_query($conn,$sql);
?> 