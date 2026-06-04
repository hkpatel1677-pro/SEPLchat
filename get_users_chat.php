<?php
                        
            if(session_status()===PHP_SESSION_NONE)
            {
                session_start();
            }
            include 'connect.php';
            $users_id=$_SESSION['users_id'];
            $sql="select DISTINCT u.*,MAX(m.id) as last_msg
                  from users u
                  JOIN messages m 
                  ON (u.id=m.users_id OR u.id=m.receiver_id)
                  WHERE u.id!='$users_id'
                  AND m.is_delete=0
                  AND (m.users_id='$users_id' OR m.receiver_id='$users_id')
                  GROUP BY u.id
                  ORDER BY last_msg DESC";
            $res=mysqli_query($conn,$sql);
            $hasChats=false;
            if(mysqli_num_rows($res)>0)
            {
                $hasChats=true;
                while($row=mysqli_fetch_assoc($res))
                    {
                        $last_message = "";
                        $query = "SELECT message, users_id FROM messages WHERE id='{$row['last_msg']}' AND is_delete='0'";
                        $mres = mysqli_query($conn, $query);
                            // $mres = mysqli_query($conn, "SELECT message, users_id FROM messages WHERE id='{$row['last_msg']}' AND is_delete='0'");
                            // print_r($query); die;
                            if(mysqli_num_rows($mres)>0)
                            {
                                $mrow = mysqli_fetch_assoc($mres);
                                $last_message =$mrow['message'];
                                if($mrow['users_id']==$users_id)
                                {
                                    $last_message = 'You: ' . $last_message;
                                }
                                else
                                {
                                    $last_message = $last_message;
                                }
                            }
                            $unread_query="select count(*) as unread_count from messages where users_id='{$row['id']}' and receiver_id='$users_id' and flag='0' and is_delete='0'";
                            $unread_res=mysqli_query($conn,$unread_query);
                            $unread_row=mysqli_fetch_assoc($unread_res);
                            $unread_count=$unread_row['unread_count'];
                        ?>
                        <form>
                            <button type="button" class="chat-user private-chat <?php echo (isset($_SESSION['receiver_id']) && $_SESSION['receiver_id']==$row['id']) ? 'active' : ''; ?>" data-id="<?php echo $row['id'];?>" data-name="<?php echo $row['username'];?>">
                                <div class="user-avatar">
                                    <?php echo strtoupper($row['username'][0]);?>
                                </div>
                                <div class="user-info">
                                    <strong>
                                        <?php echo $row['username'];?>
                                        <?php 
                                                if($unread_count>0)
                                                {
                                                    $display_count = ($unread_count>99) ? '99+' : $unread_count;
                                                    echo '<span class="unread-badge">'.$display_count.'</span>';
                                                }
                                        ?>
                                    </strong>
                                    <small class="last-message"><?php echo $last_message; ?></small>                                 
                                </div>
                            </button>
                        </form>
                        <?php
                    }
            }
                    $group_sql="SELECT g.id, g.group_name, MAX(m.id) as last_msg
                                FROM groups g
                                JOIN group_members gm ON g.id=gm.groups_id
                                LEFT JOIN messages m ON m.groups_id=g.id AND m.is_delete=0
                                WHERE gm.users_id='$users_id'
                                GROUP BY g.id
                                ORDER BY last_msg DESC";
                    $group_res=mysqli_query($conn,$group_sql);
                if(mysqli_num_rows($group_res)>0)
                {
                    $hasChats=true;
                    while($group_row=mysqli_fetch_assoc($group_res))
                        {
                            $last_message = "";
                                    $query = "SELECT m.message,m.users_id,u.username FROM messages m JOIN users u ON m.users_id=u.id WHERE m.id='{$group_row['last_msg']}' AND m.is_delete='0'";
                                    $mres = mysqli_query($conn, $query);
                                    if(mysqli_num_rows($mres)>0)
                                    {
                                            $mrow = mysqli_fetch_assoc($mres);
                                            $last_message =$mrow['message'];
                                            if($mrow['users_id']==$users_id)
                                                {
                                                    $last_message = 'You: ' . $last_message;
                                                }
                                                else
                                                {
                                                    $last_message = $mrow['username'].": ".$last_message;
                                                }
                                    }
                                    $unread_query="SELECT COUNT(*) as unread_count FROM group_message_reads gmr JOIN messages m ON gmr.message_id=m.id WHERE gmr.users_id='$users_id' AND m.groups_id='{$group_row['id']}' AND gmr.is_read='0' AND m.is_delete='0'";
                                    $unread_res=mysqli_query($conn,$unread_query);
                                    $unread_row=mysqli_fetch_assoc($unread_res);
                                    $unread_count=$unread_row['unread_count'];
                            ?>
                            <form>
                                <button type="button" class="chat-user group-chat <?php echo (isset($_SESSION['groups_id']) && $_SESSION['groups_id']==$group_row['id']) ? 'active' : ''; ?>" data-group-id="<?php echo $group_row['id'];?>" data-name="<?php echo $group_row['group_name'];?>">
                                    <div class="user-avatar">
                                        <?php echo strtoupper($group_row['group_name'][0]);?>
                                    </div>
                                    <div class="user-info">
                                        <strong>
                                            <?php echo $group_row['group_name'];?>
                                            <?php 
                                                if($unread_count>0)
                                                {
                                                    $display_count = ($unread_count>99) ? '99+' : $unread_count;
                                                    echo '<span class="unread-badge">'.$display_count.'</span>';
                                                }
                                            ?>
                                        </strong>
                                        <small class="last-message"><?php echo $last_message; ?></small>                                 
                                    </div>
                                </button>
                            </form>
                             <?php
                        }
                   }       
            if(!$hasChats)
                {
        ?>
        <div class="empty-list">
            No chats yet.
        </div>
        <?php
                }
        ?>