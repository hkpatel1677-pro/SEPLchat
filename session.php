<?php
   session_start();
   if(!isset($_SESSION['username']) || !isset($_SESSION['phone']) || !isset($_SESSION['email']))
    {
        header("Location:register.php");
        exit();
    }
?>