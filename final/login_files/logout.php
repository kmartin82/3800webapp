<!DOCTYPE html>
  
    
<?php
    $pageTitle = "logout";
    $login_page = true;
    include('../includes/header.php');
    include ("functions.php");

    session_start();   

    //logout current user
    unset($_SESSION['current_user']);
    $_SESSION['message'] = 'You have been logged out.';
    header('Location: login_message.php');
?>
</html>


