<?php
    include 'connect.php';
    include 'session.php';
    $current_user=$_SESSION['username'];
    $sql="select username,id from users where username!='$current_user'";
    $res=mysqli_query($conn,$sql);
    while($row=mysqli_fetch_assoc($res))
        {
            ?>
            <label>
                <input type="checkbox" class="member" value="<?php echo $row['id'];?>">
                <?php echo $row['username'];?>
            </label>
        <?php
        } 
?>