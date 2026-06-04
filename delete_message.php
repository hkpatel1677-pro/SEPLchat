<?php
    include 'connect.php';
    include 'session.php';
    date_default_timezone_set('Asia/Kolkata');
    $id=$_POST['id'];
    $updated_by=$_SESSION['username'];
    $updated_at=date('Y-m-d H:i:s');
    $sql="update messages set is_delete=1,updated_at='$updated_at',updated_by='$updated_by' where id=$id";
    mysqli_query($conn,$sql);
?>