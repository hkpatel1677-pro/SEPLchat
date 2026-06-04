<?php
    include 'connect.php';
    include 'session.php';
    $members=$_POST['members'];
    $group_name=trim($_POST['name']);
    $created_by=$_SESSION['username'];
    $current_user_id=$_SESSION['users_id'];
    $sql="insert into groups(group_name,created_by) values('$group_name','$created_by')";
    $res=mysqli_query($conn,$sql);
    if($res)
        {
            $groups_id=mysqli_insert_id($conn);
            $i_sql="insert into group_members(groups_id,users_id) values('$groups_id','$current_user_id')";
            mysqli_query($conn,$i_sql);
            foreach($members as $member_id)
            {
                $member_id=(int)$member_id;
                // echo $member_id; die;
                $i_sql="insert into group_members(groups_id,users_id) values('$groups_id','$member_id')";
                mysqli_query($conn,$i_sql);
            }
        }
?>