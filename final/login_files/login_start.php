

<!DOCTYPE html>  
<?php
    $pageTitle = "login start";
    $login_page = true;
    include('../includes/header.php');
?>     
    <main>
        
        <form action="login.php" method="post">
          <input type="radio" name="type" value="existing" id="existing">
          <label for="existing">I have an account.</label><br>
          <input type="radio" name="type" value="new" id="new" checked>
          <label for="new">I need to create an account</label><br>
          <input type="submit" value="Go"> 
        </form>
     
    </main>  
        <?php include('../includes/footer.php') ?>


