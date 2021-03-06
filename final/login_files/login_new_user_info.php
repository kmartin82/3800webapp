<?php
    $pageTitle = "login";
    $login_page = true;
    include('../includes/header.php');
    
    include ("functions.php");
    
    session_start();
    require_once("../includes/open_db.php");

    if(!empty($_GET)){
        add_new_user_info($db, $_SESSION['current_user'], $_GET);
        $_SESSION['message'] = 'Your account has been created and you are logged in';
        header('Location: login_message.php');
    } 
?>
        <h1>Please Enter Your Account Information User <?php $current_user = $_SESSION['current_user']; echo "$current_user";?></h1>
    </header>
    
    <main>
        <form method="get">
            <label for="first_name" class="first_name_label">First Name</label>
            <input type="text" name="first_name" id="first_name" class="text_input" required><br> 
            
            <label for="last_name" class="last_name_label">Last Name</label>
            <input type="text" name="last_name" id="last_name" class="text_input" required><br> 
            
            <label for="address" class="address_label">Address</label>
            <input type="text" name="address" id="address"  class="text_input" required><br> 
            
            <label for="city" class="city_label">City</label>
            <input type="text" name="city" id="city" class="text_input" required><br> 
            
            <label for="state" class="state_label">state</label>
            <select name="state" id="state" size="1">
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AR">AR</option>	
                <option value="AZ">AZ</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DC">DC</option>
                <option value="DE">DE</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="IA">IA</option>	
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="MA">MA</option>
                <option value="MD">MD</option>
                <option value="ME">ME</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MO">MO</option>	
                <option value="MS">MS</option>
                <option value="MT">MT</option>
                <option value="NC">NC</option>	
                <option value="NE">NE</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>			
                <option value="NV">NV</option>
                <option value="NY">NY</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WI">WI</option>	
                <option value="WV">WV</option>
                <option value="WY">WY</option>
            </select><br>
            
            <label for="zip" class="zip_label">Zip</label>
            <input type="text" id="zip" name="zip" class="text_input" pattern="[0-9]*"required><br>
            
            <label for="phone" class="phone_label">Phone</label>
            <input type="tel" id="phone" name="phone" placeholder="123-456-8888" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
            
            <input type="submit" value="Send">
            <input type="reset" value="Reset">   
        </form>

        
    </main>    
   
