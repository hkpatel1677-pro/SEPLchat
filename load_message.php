<?php
include 'session.php';
include 'connect.php';
$current_user = $_SESSION['users_id'];
$receiver_id = "";
$receiver_username = "";
$receiver_id = $_SESSION['receiver_id'] ?? "";
$groups_id = $_SESSION['groups_id'] ?? "";
if($receiver_id != "")
{
    $rec_sql = "SELECT * FROM users
                 WHERE id='$receiver_id'";
    $rec_res = mysqli_query($conn,$rec_sql);
    if(mysqli_num_rows($rec_res)>0)
    {
        $rec_row = mysqli_fetch_assoc($rec_res);
        $receiver_username = $rec_row['username'];
    }
}
if($receiver_id == "" && $groups_id == "")
{
    ?>
    <div class="empty-chat">
        <strong>Select a user</strong>
        Start chatting.
    </div>
    <?php
    exit();
}
if($groups_id!="")
{
        $sql = "SELECT m.*,u.username FROM messages m
        JOIN users u 
        ON m.users_id=u.id
        WHERE m.groups_id='$groups_id'
        AND m.is_delete=0
        ORDER BY m.created_at ASC";

$res = mysqli_query($conn,$sql);
$last_message="";
if(mysqli_num_rows($res)>0)
{
    while($row=mysqli_fetch_assoc($res))
    {
        $message_date=date('Y-m-d',strtotime($row['created_at']));
        if($message_date!=$last_message)
            {
                if($message_date==date('Y-m-d'))
                    {
                        $label="Today";
                    }
                else if($message_date==date('Y-m-d',strtotime('-1 day')))
                    {
                        $label="Yesterday";
                    }
                else
                    {
                        $label=date('d M Y',strtotime($message_date));
                    }    
                echo '<div class="date-divider"><span>'.$label.'</span></div>';
                $last_message=$message_date;
            }

        $messageTime = date('h:i A', strtotime($row['created_at']));
        if($row['users_id']==$current_user)
        {
            ?>

         <div class="message-row mine">
           <div class="bubble-wrapper">
             <div class="msg-menu">
                <button class="menu-btn">
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="menu-dropdown">
                    <button 
                        class="delete-msg"
                        data-id="<?php echo $row['id']; ?>"
                    >
                        Delete
                    </button>
                </div>
             </div>

                <div class="bubble">
                    <div class="meta">
                        <span class="label">
                            You
                        </span>
                            <span class="time"><?php echo $messageTime; ?></span>
                    </div>
                    <p>
                        <?php echo $row['message']; ?>
                    </p>
                </div>
            </div>
            </div>
            <?php
        }
        else
        {
            ?>
            <div class="message-row">
                <div class="bubble">

                    <div class="meta">
                        <span class="label">
                            <?php echo $row['username']; ?>
                        </span>
                        <span class="time"><?php echo $messageTime; ?></span>
                    </div>
                    <p>
                        <?php echo $row['message']; ?>
                    </p>
                </div>
            </div>
            <?php
        }
    }
}
else
{
    ?>
    <div class="empty-chat">
        <strong>No messages yet</strong>
        Start the conversation.
    </div>
    <?php
}
    exit();
}
?>
<?php
$sql = "SELECT * FROM messages
        WHERE 
        (
        (users_id='$current_user'
        AND receiver_id='$receiver_id')

        OR (users_id='$receiver_id'
        AND receiver_id='$current_user')
        )
        AND is_delete=0
        ORDER BY created_at ASC";

$res = mysqli_query($conn,$sql);
$last_message="";
if(mysqli_num_rows($res)>0)
{
    while($row=mysqli_fetch_assoc($res))
    {
        $message_date=date('Y-m-d',strtotime($row['created_at']));
        if($message_date!=$last_message)
            {
                if($message_date==date('Y-m-d'))
                    {
                        $label="Today";
                    }
                else if($message_date==date('Y-m-d',strtotime('-1 day')))
                    {
                        $label="Yesterday";
                    }
                else
                    {
                        $label=date('d M Y',strtotime($message_date));
                    }    
                echo '<div class="date-divider"><span>'.$label.'</span></div>';
                $last_message=$message_date;
            }

        $messageTime = date('h:i A', strtotime($row['created_at']));
        if($row['users_id']==$current_user)
        {
            ?>
         <div class="message-row mine">
           <div class="bubble-wrapper">
             <div class="msg-menu">
                <button class="menu-btn">
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="menu-dropdown">

                    <button 
                        class="delete-msg"
                        data-id="<?php echo $row['id']; ?>"
                    >
                        Delete
                    </button>
                </div>
             </div>
                <div class="bubble">

                    <div class="meta">
                        <span class="label">
                            You
                        </span>
                            <span class="time"><?php echo $messageTime; ?></span>
                    </div>
                    <p>
                        <?php echo $row['message']; ?>
                    </p>
                </div>
            </div>
            </div>
            <?php
        }
        else
        {
            ?>
            <div class="message-row">
                <div class="bubble">

                    <div class="meta">
                        <span class="label">
                            <?php echo $receiver_username; ?>
                        </span>
                        <span class="time"><?php echo $messageTime; ?></span>
                    </div>
                    <p>
                        <?php echo $row['message']; ?>
                    </p>
                </div>
            </div>
            <?php
        }
    }
}
else
{
    ?>
    <div class="empty-chat">
        <strong>No messages yet</strong>
        Start the conversation.
    </div>
    <?php
}
?>