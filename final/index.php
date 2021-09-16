<?php 
  $pageTitle = "Game Center";
  $login_page = false;
  include('includes/header.php');
  include('includes/functions.php');
  
  require_once('includes/open_db.php');
  session_start(); 
?>
		
<nav>
  <a href="cart.php"><img src="img/cart.png" alt="cart" class="cart"></a>
  
  
  <?php
    // if the current user is set then the page will display the account and login button 
    if (isset($_SESSION['current_user'])){ 
        $current_user = $_SESSION['current_user'];
        echo "<h1>Welcome $current_user</h1>";
        echo "<form action='login_files/logout.php' method='post'>";
        echo "<input type='submit' name='logout' value='logout'>";
        echo "</form>";
        echo "<form action='user_account.php' method='post'>";
        echo "<input type='submit' name='Account' value='Account'>";
        echo "</form>";
    }
    
    // if the user is not logged in the login/create buttton will show 
    else {
        echo "<form action='login_files/login_start.php' method='post'>";
        echo "<input type='submit' value='Login/create account'>";
        echo "</form>";
    }   
  ?>
</nav>

  <?php
    // boolean for out of stock
    $out_of_stock = false;
    
    // checks if user is logged in when trying to add item to cart display message if not logged in
    if (!isset($_SESSION['current_user']) && isset($_POST['upc'])) {
        echo 'You must be logged in to add item!';
        unset($_POST['upc']); 
    }
    // if user is logged in and trys to add item
    else if (isset($_SESSION['current_user']) && isset($_POST['upc'])) {
        $upc = $_POST['upc'];
        
        // function returns true if item is out of stock false if in stock 
        $out_of_stock = add_item_to_cart($db, $upc, $_SESSION['current_user']);
        
        // alert user if item is out of stock
        if ($out_of_stock) {
            echo 'Item is out of stock!';
        }
        unset($_POST['upc']);      
    }
      
    
  ?> 

  <main class="flex_content">     <!-- needed so that main in other pages won't be styled as a flex box; could have used separate css pages to avoid this -->     
  <?php
  
    //display each hat in the inventory
    $inv_html = "";
    foreach(get_inventory($db) as $game) { 
        $inv_html = get_info_html($game) . $inv_html;
    }
    echo $inv_html;              
  ?> 

</main>

<?php include('includes/footer.php') ?>