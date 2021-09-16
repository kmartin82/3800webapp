<?php
    $pageTitle = "Cart";
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
  
    // if user clicks cancel order button empty cart
    if (isset($_POST['cancel'])) {
        empty_cart($db, $_SESSION['current_user']);
        unset($_POST['cancel']);
    }
    
    // if user confirms order check to see if any zero quantitys in cart
    // alert user to remove item if zero
    // if no zero direct to confrim.php
    if (isset($_POST['confirm'])) {
        // get current cart
        $cart = get_cart($db, $_SESSION['current_user']);
        
        // boolean to see if cart has no zero quantity values
        $no_zeros = true;
        
        // loop through cart
        foreach ($cart as $item) {
            // if item in carts quantity is zero alert user
            if($item['quantity'] == 0) {
                // javscript alert
                echo "<script type='text/javascript'>
                alert('Games Cannot Have a Quanity Of Zero Please Edit Cart.');
                </script>";
                // set no_zeros to true
                $no_zeros = false;
                
                // break from loop when first zero is found
                break;
            } 
        }
        
        // unset post confirm
        unset($_POST['confrim']);
        
        // if no zeros were found redirect to confirm page
        if ($no_zeros){
            // redirect to confirm.php
            header('Location: confirm.php');
            exit;
        }
        
    }
    
    // if user clicks add to quantity button call add item to cart
    // if out of stock alert user 
    if(isset($_POST['add'])) {
        $upc = $_POST['add'];
        // call add item to cart which returns true or false depending on if there is stock 
        $out_of_stock = add_item_to_cart($db, $upc, $_SESSION['current_user']);
      
        // if no stock alert user 
        if ($out_of_stock) {
            echo "<script type='text/javascript'>
            alert('Item has No More Stock!');
            </script>"; 
        }
        
        // unset post add
        unset($_POST['add']); 
    }
    
    // if user clicks sub button call remove one quantity from cart 
    if(isset($_POST['sub'])) {
        $upc = $_POST['sub'];
        remove_one_item_from_cart($db, $upc, $_SESSION['current_user']);
        unset($_POST['sub']);
    }

    // if user clicks delete button call delete item from cart
    if(isset($_POST['delete'])) {
        $upc = $_POST['delete'];
        delete_item_from_cart($db, $upc, $_SESSION['current_user']);
        unset($_POST['delete']);
    }
  
    // if cart is empty dispaly empty cart message
    if (count(get_cart($db, $_SESSION['current_user'])) == 0) {
        echo '<section class="empty_cart"><p>Your cart is empty.</p>';
        echo '<a href="index.php"><input type="button" value="Continue Shopping"></a></section>';
    } 
    // else an item is in the cart display table
    else {
?>
    <main>     
        <table>
          <thead>
            <tr>
              <th colspan="7" id="table_title"> <?php $current_user = $_SESSION['current_user']; echo "$current_user";?>'s Current Order Summary</th>
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
            
            // get cart of current user
            $cart = get_cart($db, $_SESSION['current_user']);

            // loop through cart and display info on each item
            foreach ($cart as $item) {
                // get game details using upc and database 
                $game = get_game_info($item['upc'], get_inventory($db));
                
                // set upc to item upc
                $upc = $item['upc'];
            ?>
                <tr>
                   <td><?php echo $game['title']; ?></td>
                   <td><?php echo $item['quantity']; ?></td>
                   <td><?php echo sprintf("$%.2f",$game['price']); ?></td>
                   <td><?php echo sprintf("$%.2f", calc_item_total($game, $item)); ?></td>
                   <td> <form action="" method='post'>
                            <input type="hidden" name="add" value="<?php echo $item['upc']?>">
                            <input type="submit" value="+">        
                        </form>
                   </td>
                   <td> <form action="" method='post'>
                            <input type="hidden" name="sub" value="<?php echo $item['upc']?>">
                            <input type="submit" value="-">        
                        </form>
                   </td>
                   <td> <form action="" method='post'>
                            <input type="hidden" name="delete" value="<?php echo $item['upc']?>">
                            <input type="submit" value="delete">        
                        </form>
                   </td>
                </tr>

           <?php 
                // calculate order total and diaplay at bottom of table
                $order_total = calc_order_total($db, $_SESSION['current_user']);
             }           
           ?>         
          </tbody>
              <tfoot>
                <tr>
                  <td>Order Total</td>
                  <td colspan="6"><?php echo sprintf("$%.2f",$order_total); ?></td>
                </tr>
              </tfoot>
          </table>
          <a href="index.php"><input type="button" value="Continue Shopping"></a>

          <form action="cart.php" method="post" id="cancel_form">
            <input type="submit" name="cancel" value="Cancel Order">
          </form>

          <form action="cart.php" method="post" id="confrim_form">
            <input type="submit" name="confirm" value="place Order">
          </form>


    </main>

<?php
} // end else
include('includes/footer.php');?>

