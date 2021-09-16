<?php 
  $pageTitle = "Order Confirmation";
  $login_page = false;
  include('includes/header.php');   
  include('includes/functions.php'); 
  
  require_once('includes/open_db.php');
  session_start(); 
  
  // check to see if user is logged in send back to index.php if not not logged in
  if (!isset($_SESSION['current_user'])) {
    echo "<script type='text/javascript'>
    alert('You must be logged in');
    location='index.php';
    </script>"; 
  }
  
?>
		
  <main class="confirm_notice">
    <h2>Order Confirmation</h2>
    
    <?php
        // formated order total
        $formatted_total = sprintf("$%.2f", calc_order_total($db, $_SESSION['current_user']));
        
        // update inventory 
        update_inventory($db, $_SESSION['current_user']);
        
        // add order to order database
        add_order($db, $_SESSION['current_user']);
      
        // empty the cart for the current user
        empty_cart($db, $_SESSION['current_user']);
        
        // get total order number to be displayed to the user
        $orderNumber = get_number_of_orders($db); 
        
        // display current date and time of order to user
        echo "<p>Order Date and Time: ". date('F j' . ', ' . 'Y')." at ".date('g:i a')."</p>";
        
        // display completed order number to user 
        echo "<p>Order number: #$orderNumber";
        
        // display order total to user
        echo "<p>Order Total: $formatted_total</p>";
      
        echo '<a href="index.php"><input type="button" value="Continue Shopping"></a>';

    ?>
        
  </main>

<?php include('includes/footer.php') ?>