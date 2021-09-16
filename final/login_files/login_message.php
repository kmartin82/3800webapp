<?php
    $pageTitle = "login messages";
    $login_page = true;
    include('../includes/header.php');
    
    session_start(); 
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
?>

<!DOCTYPE html>
</header>
    <main> 
        <?php          
          echo "<h1>$message</h1>"; 
          echo "You will be redirected to the home page in 2 seconds.";
          header( "refresh:2; url=../index.php" );
        ?>
    </main>        
        <?php include('../includes/footer.php') ?>


