<?php
    include 'session.php';
    include 'connect.php';
    $users_id=$_SESSION['users_id'];
    $username=$_SESSION['username'];
    $message=trim($_POST['message'] ?? '');
    $groups_id = $_SESSION['groups_id'] ?? "";
    $receiver_id = $_SESSION['receiver_id'] ?? "";
    if($groups_id!="")
        {
            $sql="insert into messages(users_id,groups_id,message,created_by) values('$users_id','$groups_id',?,'$username')";
        }
    else
        {
            $sql="insert into messages(users_id,receiver_id,message,created_by) values('$users_id','$receiver_id',?,'$username')";       
        }
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,"s",$message);
        mysqli_stmt_execute($stmt); 
    if($groups_id!="")
        {
            $message_id=mysqli_insert_id($conn);
            $m_sql="select users_id from group_members where groups_id='$groups_id' AND users_id!='$users_id'";
            $m_res=mysqli_query($conn,$m_sql);
            if(mysqli_num_rows($m_res)>0)
                {
                    while($m_row=mysqli_fetch_assoc($m_res))
                        {
                            $member_id=$m_row['users_id'];
                            $i_sql="insert into group_message_reads(message_id,users_id) values('$message_id','$member_id')";
                            mysqli_query($conn,$i_sql);
                        }
                }
        }  
?>