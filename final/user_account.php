<?php 
  $pageTitle = "Account Details";
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
    <main>
        <h1>Welcome <?php $current_user = $_SESSION['current_user']; echo "$current_user";?></h1>
        <h3>Order History</h3>

    <?php
        // check if user has any orders 
        if (count(get_number_of_orders_by_user($db, $_SESSION['current_user'])) == 0){
            echo '<section class="no_orders"><p>You have no order History.</p>';
            echo '<a href="index.php"><input type="button" value="Return to Homepage"></a></section>';
            echo '</main>';
        }
        // user has orders
        else {
            // get all the current users orders
            $user_orders = get_number_of_orders_by_user($db, $_SESSION['current_user']);
            // loop through current users orders and display 
            foreach ($user_orders as $order){
    ?>
      <table>
        <thead>
          <tr>
             <th colspan="4"> Order # <?php $order_id = $order['orderid']; echo "$order_id";?></th>
          </tr>
          <tr>
              <th>Game</th>
              <th>Quantity</th>
              <th>Unit Price</th>
              <th>Total Price</th>
          </tr>
        </thead>
        <tbody>
        <?php  
        $order_total = 0;
        
        // get order details for current user and id
        $items = get_order_details($db, $_SESSION['current_user'], $order['orderid']);
        
        // loop through and display each item of the order in table 
        foreach ($items as $item) {
            // get game details 
            $game = get_game_info($item['upc'], get_inventory($db));
        ?>
            <tr>
               <td><?php echo $game['title']; ?></td>
               <td><?php echo $item['quantity']; ?></td>
               <td><?php echo sprintf("$%.2f",$game['price']); ?></td>
               <td><?php echo sprintf("$%.2f", calc_item_total($game, $item)); ?></td>
            </tr>
       <?php 
            // calculate order total and diaplay at bottom of table
            $order_total = get_order_total_history($db, $_SESSION['current_user'], $order['orderid']);
         }           
       ?>         
        </tbody>
            <tfoot>
              <tr>
                <td>Order Total</td>
                <td colspan="3"><?php echo sprintf("$%.2f",$order_total); ?></td>
              </tr>
              <tr>
                <td>Order Date</td>
                <td colspan="3"><?php echo $order['orderdate']; ?></td>
              </tr>
            </tfoot>
        </table>
<?php
        } // end foreach
        // display return to home button
        echo '<a href="index.php"><input type="button" value="Return to Homepage"></a>';
} // end else
?>
 
    </main>
<?php include('includes/footer.php');?>