<?php

// function return inventory from database
function get_inventory($db) {
    $query = 'select * from inventory';
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor(); 
    return $results;
}

function one_inventory($db) {
    $query = 'select * from inventory';
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor(); 
    return $results;
}

function get_title_inventory($db, $upc) {
    $query = 'select * from inventory where upc = :upc';
    $statement = $db->prepare($query);
    $statement->bindValue(':upc', $upc);
    $statement->execute();
    $results = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $results['description'];
}

// function adds item to cart if quantity in stock
// returns false if item was added to cart
// return true if item is not added to cart 
function add_item_to_cart($db, $upc, $user_name) {
    // get quantity in inventory
    $quantity_inventory = get_quantity_available($upc, $db);
    
    // get quantity currently in cart
    $quantity_cart = get_quantity_in_cart($upc, $db, $user_name);
    
    // if the quantity in inventory is greater then quantity in cart 
    // add one to quantity in cart and return false
    if ($quantity_inventory > $quantity_cart) {
        $query = 'INSERT INTO cart (upc, quantity, username) VALUES(:upc, 1, :username) ON DUPLICATE KEY UPDATE quantity = quantity+1';
        $statement = $db->prepare($query);
        $statement->bindValue(':upc', $upc);
        $statement->bindValue(':username', $user_name);
        $statement->execute();
        $statement->closeCursor();      
        return false;
    }
    // else quantity in cart is equal to quantity in inventoy 
    // do not add to quantity in cart and return true
    else {
        return true;
    } 
}

// function removes one of items quantity from cart until quantity = 0
function remove_one_item_from_cart($db, $upc, $user_name) {
    // get quantity currently in cart for item
    $quantity_cart = get_quantity_in_cart($upc, $db, $user_name);
    
    // if quantity in cart is greater than zero remove one from cart 
    if ($quantity_cart > 0) {
        $query = 'INSERT INTO cart (upc, quantity, username) VALUES(:upc, 1, :username) ON DUPLICATE KEY UPDATE quantity = quantity-1';
        $statement = $db->prepare($query);
        $statement->bindValue(':upc', $upc);
        $statement->bindValue(':username', $user_name);
        $statement->execute();
        $statement->closeCursor();      
    }
}

// function removes item completely from cart based on upc and username
function delete_item_from_cart($db, $upc, $user_name) {
    $query = 'delete FROM cart where username = :username and upc = :upc';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $user_name);
    $statement->bindValue(':upc', $upc);
    $statement->execute();
    $statement->closeCursor(); 
}

// function returns cart based on username
function get_cart($db, $user_name) {
    $query = 'select * from cart where username = :username';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $user_name);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor(); 
    return $results;
}

// function calculates order total
function calc_order_total($db, $user_name) {
    // get current users cart
    $cart = get_cart($db, $user_name);
    
    $order_total = 0; $item_total = 0; $quantity = 0; $price = 0;
         
    // loop through all items in cart and add up total
    foreach($cart as $item) {
        $game = get_game_info($item['upc'], get_inventory($db));
        $price = $game['price'];
        $quantity = $item['quantity'];
        
        $item_total = calc_item_total($game, $item);
        
        $order_total += $item_total;
    }
    return $order_total;
}

// function calculates price of itme 
function calc_item_total($game, $item) {
    // set games price
    $price = $game['price'];
    
    // set quantity of item
    $quantity = $item['quantity'];
    
    // return price x quantity
    return ($price * $quantity);
}

// function removes all items of cart from current user 
function empty_cart($db, $user_name) {
    $query = 'delete FROM cart where username = :username';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $user_name);
    $statement->execute();
    $statement->closeCursor(); 
}

// function returns the quantity in stock in the inventory
// for upc passed in
function get_quantity_available($upc, $db) {
    $inventory = get_inventory($db);
    
    // loop through all items in inventory
    foreach($inventory as $item) {
        // if item in inventory matches upc passed in 
        // return quantity in inventory
        if($item['upc'] == $upc) {
            return $item['quantity'];
        }
    }
}

// function returns quantity in cart for upc and username passed in 
function get_quantity_in_cart($upc, $db, $user_name) {
    $cart = get_cart($db, $user_name);
    
    // loop through all items in cart 
    foreach($cart as $item) {
        // if item in cart macthes upc passed in
        // return quantity in cart
        if($item['upc'] == $upc) {
            return $item['quantity'];
        }
    }
}

// function updates inventory based number in cart at confirm order
function update_inventory($db, $user_name) {
    $cart = get_cart($db, $user_name);
    
    foreach($cart as $item) {
        $new_quantity = 0; 
        $cart_quantity = $item['quantity'];
        $upc = $item['upc'];
        $quantity_available = get_quantity_available($upc, $db);
        
        $new_quantity = ($quantity_available - $cart_quantity);
        
        $query = 'UPDATE inventory SET quantity = :quantity WHERE upc = :upc';
        $statement = $db->prepare($query);
        $statement->bindValue(':upc', $upc);
        $statement->bindValue(':quantity', $new_quantity);
        $statement->execute();
        $statement->closeCursor(); 
    }
}

// function adds to order table
function add_order($db, $user_name) {
    $cart = get_cart($db, $user_name);
    $orderid = (get_number_of_orders($db) + 1);
    $order_date = date('Y-m-d H:i:s');
    
    foreach ($cart as $item) {
        $game = get_game_info($item['upc'], get_inventory($db));
        
        $query = 'INSERT INTO orders (orderid, upc, username, quantity, price, orderdate) VALUES(:orderid, :upc, :username, :quantity, :price, :orderdate)';
        $statement = $db->prepare($query);
        $statement->bindValue(':orderid', $orderid);
        $statement->bindValue(':upc', $item['upc']);
        $statement->bindValue(':username', $item['username']);
        $statement->bindValue(':quantity', $item['quantity']);
        $statement->bindValue(':price', calc_item_total($game, $item));
        $statement->bindValue(':orderdate', $order_date);

        $statement->execute();
        $statement->closeCursor(); 
    }
}

// function gets total number of orders
function get_number_of_orders($db) {
    $query = 'SELECT DISTINCT orderid FROM `orders`';
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor(); 
    return count($results);
}

// function gets total number of orders for a passed in user
function get_number_of_orders_by_user($db, $user_name){
    $query = 'SELECT DISTINCT orderid, orderdate FROM `orders` where username = :username';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $user_name);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor(); 
    return $results;
} 

// function gets order details for user and orderid passed in
function get_order_details($db, $user_name, $orderid){
    $query = 'SELECT * FROM `orders` where username = :username and orderid = :orderid';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $user_name);
    $statement->bindValue(':orderid', $orderid);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor(); 
    return $results;
}

// function calclulates order total for past orders
function get_order_total_history($db, $user_name, $orderid){
    $order = get_order_details($db, $user_name, $orderid);
    
    $order_total = 0;
    $item_total = 0;
    $quantity = 0;
    $price = 0;
         
    foreach($order as $item) {
        $game = get_game_info($item['upc'], get_inventory($db));
        $price = $game['price'];
        $quantity = $item['quantity'];
        
        $item_total = calc_item_total($game, $item);
        
        $order_total += $item_total;
    }
    return $order_total;
}

function get_info_html($game) {
  
    $image_file = "img/game_images/".$game['upc'].".jpg";
    
    $formatted_price = sprintf("$%.2f",$game['price']);
    
    $html_out = "";
    
    
    $html_out = <<<EOD
        <figure>
          <img src="{$image_file}" alt="{$game['upc']}">
          <figcaption>
            <span class='title'>{$game['title']}</span><br>
            {$formatted_price}<br>
            Quantity available: {$game['quantity']}<br>
            <form action="." method='post'>
              <input type="hidden" name="upc" value={$game['upc']}>
              <input type="submit" value="Add to Cart">        
            </form>
        </figcaption>
      </figure>
EOD;
    
    return $html_out;
 
 }
 
 function get_game_info($upc, $inventory) {
    foreach ($inventory as $game) {
      if ($game['upc'] == $upc) {
        return $game;
      }
    }      
  }
 
?>